<?php

namespace Mtt\BlogBundle\Service;

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
                $username = 'undefined';
                if ($user = $comment->getUser()) {
                    $username = $user->getUsername();
                } elseif ($commentator = $comment->getCommentator()) {
                    $username = $commentator->getName();
                }

                $context = $this->twig->mergeGlobals([
                    'topicTitle' => $comment->getPost()->getTitle(),
                    'topicUrl' => '/article/' . $comment->getPost()->getUrl(),
                    'username' => $username,
                    'commentText' => $comment->getText(),
                    'avatar' => $comment->getAvatarHash() . '.png',
                ]);

                $template = $this->twig->load('MttBlogBundle:mails:replyComment.html.twig');
                $textTemplate = $this->twig->load('MttBlogBundle:mails:replyComment.txt.twig');

                $message = Swift_Message::newInstance()
                    ->setSubject('Ответ на комментарий')
                    ->setFrom($this->emailFrom)
                    ->setTo([$emailTo => $recipient])
                    ->addPart(
                        $template->render($context),
                        'text/html'
                    )
                    ->addPart(
                        $textTemplate->render($context),
                        'text/plain'
                    )
                ;

                $this->mailer->send($message);
            }
        }
    }
}
