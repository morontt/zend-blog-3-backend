<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 21:11
 */

namespace Mtt\BlogBundle\EventListener;

use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Event\CommentEvent;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment as TwigEnvironment;
use Twig\Error\Error;

class ReplyCommentListener
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var TwigEnvironment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $emailFrom;

    /**
     * @param Swift_Mailer $mailer
     * @param TwigEnvironment $twig
     * @param string $emailFrom
     */
    public function __construct(Swift_Mailer $mailer, TwigEnvironment $twig, string $emailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->emailFrom = $emailFrom;
    }

    /**
     * @param CommentEvent $event
     *
     * @throws Error
     */
    public function onReply(CommentEvent $event)
    {
        $comment = $event->getComment();
        $this->sendEmail($comment);
    }

    /**
     * @param Comment $comment
     *
     * @throws Error
     */
    protected function sendEmail(Comment $comment)
    {
        $parent = $comment->getParent();
        if ($parent) {
            $emailTo = null;
            $recipient = 'undefined';
            if ($user = $parent->getUser()) {
                $emailTo = $user->getEmail();
                $recipient = $user->getUsername();
            } elseif ($commentator = $parent->getCommentator()) {
                $emailTo = $commentator->getEmail();
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
                ]);

                $template = $this->twig->load('MttBlogBundle:mails:replyComment.html.twig');

                $message = Swift_Message::newInstance()
                    ->setSubject('Ответ на комментарий')
                    ->setFrom($this->emailFrom)
                    ->setTo([$emailTo => $recipient])
                    ->setBody(
                        $template->render($context),
                        'text/html'
                    )
                ;

                $this->mailer->send($message);
            }
        }
    }
}
