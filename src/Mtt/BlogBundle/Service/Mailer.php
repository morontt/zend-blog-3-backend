<?php

namespace Mtt\BlogBundle\Service;

use Mtt\BlogBundle\DTO\EmailMessageDTO;
use Mtt\BlogBundle\Entity\Comment;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment as TwigEnvironment;
use Xelbot\Telegram\Robot;

class Mailer
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var TwigEnvironment
     */
    private $twig;

    /**
     * @var string
     */
    private $emailFrom;

    /**
     * @var Robot
     */
    private $bot;

    /**
     * @param Swift_Mailer $mailer
     * @param TwigEnvironment $twig
     * @param Robot $bot
     * @param string $emailFrom
     */
    public function __construct(Swift_Mailer $mailer, TwigEnvironment $twig, Robot $bot, string $emailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->emailFrom = $emailFrom;
        $this->bot = $bot;
    }

    public function replyComment(Comment $comment)
    {
        try {
            $this->sendEmailOnReply($comment);
        } catch (\Throwable $e) {
            $this->bot->sendMessage('onReply comment error: ' . $e->getMessage());
        }
    }

    public function newComment(Comment $comment, string $emailTo, bool $spool = true)
    {
        $context = $this->twig->mergeGlobals($this->context($comment));

        $template = $this->twig->load('MttBlogBundle:mails:newComment.html.twig');
        $textTemplate = $this->twig->load('MttBlogBundle:mails:newComment.txt.twig');

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
        $successfullySent = false;
        try {
            $message = Swift_Message::newInstance()
                ->setSubject($messageDTO->subject)
                ->setFrom($messageDTO->from)
                ->setTo($messageDTO->to)
            ;

            if ($messageDTO->messageHtml) {
                $message->addPart($messageDTO->messageHtml, 'text/html');
            }
            if ($messageDTO->messageText) {
                $message->addPart($messageDTO->messageText, 'text/plain');
            }

            $successfullySent = $this->mailer->send($message) > 0;
        } catch (\Throwable $e) {
            $this->bot->sendMessage(
                'email sent error: ' . $e->getMessage()
                . "\n\nfile: " . $e->getFile()
                . "\nline: " . $e->getLine()
            );
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
        try {
            $randomBytes = bin2hex(random_bytes(3));
        } catch (\Exception $e) {
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

    public function spoolSend($messageLimit = null, $timeLimit = null): int
    {
        $count = 0;
        $time = time();
        foreach (new \DirectoryIterator($this->spoolPath()) as $fileInfo) {
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

    private function sendEmailOnReply(Comment $comment)
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
                $context = $this->twig->mergeGlobals($this->context($comment));

                $template = $this->twig->load('MttBlogBundle:mails:replyComment.html.twig');
                $textTemplate = $this->twig->load('MttBlogBundle:mails:replyComment.txt.twig');

                $message = new EmailMessageDTO();

                $message->subject = 'Ответ на комментарий';
                $message->from = $this->emailFrom;
                $message->to = [$emailTo => $recipient];
                $message->messageHtml = $template->render($context);
                $message->messageText = $textTemplate->render($context);

                $this->queueMessage($message);
            }
        }
    }

    private function spoolPath(): string
    {
        return APP_VAR_DIR . '/spool';
    }

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
}
