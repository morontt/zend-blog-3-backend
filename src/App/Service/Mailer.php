<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\EmailMessageDTO;
use App\Entity\Comment;
use App\Entity\EmailSubscriptionSettings;
use App\Repository\EmailSubscriptionSettingsRepository;
use App\Utils\HashId;
use App\Utils\VerifyEmail;
use DirectoryIterator;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Throwable;
use Twig\Environment as TwigEnvironment;
use Xelbot\Telegram\Robot;

class Mailer
{
    public function __construct(
        private MailerInterface $mailer,
        private TwigEnvironment $twig,
        private Robot $bot,
        private EmailSubscriptionSettingsRepository $subscriptionRepository,
        private LoggerInterface $logger,
        private string $frontendSite,
        private string $emailFrom,
    ) {
    }

    public function newComment(Comment $comment, string $emailTo, bool $spool = true): void
    {
        $emailTo = VerifyEmail::normalize($emailTo);
        $context = $this->context($comment);

        $template = $this->twig->load('mails/newComment.html.twig');
        $textTemplate = $this->twig->load('mails/newComment.txt.twig');

        $message = new EmailMessageDTO();

        $message->subject = 'Новый комментарий';
        $message->from = $this->emailFrom;
        $message->to = $emailTo;
        $message->messageHtml = $template->render($context);
        $message->messageText = $textTemplate->render($context);

        $spool ? $this->queueMessage($message) : $this->send($message);
    }

    public function send(EmailMessageDTO $messageDTO): bool
    {
        if ($this->isBlocked($messageDTO)) {
            // successfully sent to black hole :)
            return true;
        }

        $successfullySent = true;
        try {
            $message = (new Email())
                ->subject($messageDTO->subject)
                ->from($this->addressFrom($messageDTO->from))
                ->to($this->addressFrom($messageDTO->to))
            ;

            if ($messageDTO->messageHtml) {
                $message->html($messageDTO->messageHtml);
            }
            if ($messageDTO->messageText) {
                $message->text($messageDTO->messageText);
            }

            if ($messageDTO->unsubscribeLink) {
                $headers = $message->getHeaders();
                $headers->addTextHeader(
                    'List-Unsubscribe',
                    sprintf('<%s%s>', $this->frontendSite, $messageDTO->unsubscribeLink)
                );
                $headers->addTextHeader('List-Unsubscribe-Post', 'List-Unsubscribe=One-Click');
            }

            $this->mailer->send($message);
        } catch (Throwable $e) {
            $this->logger->error('email sent error', ['exception' => $e]);
            $this->bot->sendMessage(
                'email sent error: ' . $e->getMessage()
                . "\n\nfile: " . $e->getFile()
                . "\nline: " . $e->getLine()
            );

            $successfullySent = false;
        }

        return $successfullySent;
    }

    /**
     * Save message to spool
     *
     * @param EmailMessageDTO $messageDTO
     */
    public function queueMessage(EmailMessageDTO $messageDTO): void
    {
        if ($this->isBlocked($messageDTO)) {
            return;
        }

        try {
            $randomBytes = bin2hex(random_bytes(3));
        } catch (Exception $e) {
            $randomBytes = dechex(mt_rand(0, 255) + 256 * mt_rand(0, 255) + 65536 * mt_rand(0, 255));
        }

        $fileName = sprintf(
            '%s/%X%s.message',
            $this->spoolPath(),
            (int)date('U'),
            strtoupper($randomBytes)
        );

        $fp = fopen($fileName, 'wb');
        fwrite($fp, serialize($messageDTO));
        fclose($fp);
    }

    public function spoolSend(?int $messageLimit = null, ?int $timeLimit = null): int
    {
        $count = 0;
        $time = time();
        foreach (new DirectoryIterator($this->spoolPath()) as $fileInfo) {
            $file = $fileInfo->getRealPath();
            if (substr($file, -8) !== '.message') {
                continue;
            }

            if (rename($file, $file . '.sending')) {
                $message = unserialize(
                    file_get_contents($file . '.sending'),
                    ['allowed_classes' => [EmailMessageDTO::class]]
                );
                if ($this->send($message)) {
                    $count++;
                    unlink($file . '.sending');
                }
            } else {
                continue;
            }

            if ($messageLimit && $count >= $messageLimit) {
                break;
            }
            if ($timeLimit && (time() - $time) >= $timeLimit) {
                break;
            }
        }

        return $count;
    }

    public function replyComment(Comment $comment): void
    {
        $parent = $comment->getParent();
        if ($parent) {
            $emailTo = null;
            $recipient = 'undefined';
            if ($user = $parent->getUser()) {
                $emailTo = $user->getEmail();
                $recipient = $user->getUsername();
            } elseif ($commentator = $parent->getCommentator()) {
                $emailTo = $commentator->isValidEmail() ? $commentator->getEmail() : null;
                $recipient = $commentator->getName();
            }

            if ($emailTo) {
                $emailTo = VerifyEmail::normalize($emailTo);
                $unsubscribeLink = $this->unsubscribeLink($emailTo, EmailSubscriptionSettings::TYPE_COMMENT_REPLY);

                $context = array_merge(
                    $this->context($comment),
                    [
                        'unsubscribeLink' => $unsubscribeLink,
                    ],
                );

                $template = $this->twig->load('mails/replyComment.html.twig');
                $textTemplate = $this->twig->load('mails/replyComment.txt.twig');

                $message = new EmailMessageDTO();

                $message->subject = 'Ответ на комментарий';
                $message->from = $this->emailFrom;
                $message->to = [$emailTo => $recipient];
                $message->messageHtml = $template->render($context);
                $message->messageText = $textTemplate->render($context);
                $message->unsubscribeLink = $unsubscribeLink;

                $this->queueMessage($message);
            }
        }
    }

    private function spoolPath(): string
    {
        return APP_VAR_DIR . '/spool';
    }

    /**
     * @return array<string, string>
     */
    private function context(Comment $comment): array
    {
        $username = 'undefined';
        if ($user = $comment->getUser()) {
            $username = $user->getUsername();
        } elseif ($commentator = $comment->getCommentator()) {
            $username = $commentator->getName();
        }

        return [
            'topicTitle' => $comment->getPost()->getTitle(),
            'topicUrl' => '/article/' . $comment->getPost()->getUrl(),
            'username' => $username,
            'commentText' => $comment->getText(),
            'avatar' => $comment->getAvatarHash() . '.png',
        ];
    }

    private function isBlocked(EmailMessageDTO $messageDTO): bool
    {
        if ($messageDTO->type === 0) {
            return false;
        }

        $email = $messageDTO->getRecipientEmail();
        $settings = $this->subscriptionRepository->findOneBy(['email' => $email, 'type' => $messageDTO->type]);

        return $settings && $settings->isBlockSending();
    }

    private function unsubscribeLink(string $email, int $type): string
    {
        $settings = $this->subscriptionRepository->findOrCreate($email, $type);
        $hash = HashId::hash($settings->getId(), mt_rand(1, 9999));

        return '/email-unsubscribe/' . $hash;
    }

    /**
     * @param string|array $recipient
     *
     * @phpstan-ignore missingType.iterableValue
     */
    private function addressFrom($recipient): Address
    {
        if (is_array($recipient)) {
            $email = array_key_first($recipient);

            return new Address($email, $recipient[$email]);
        }

        return new Address($recipient);
    }
}
