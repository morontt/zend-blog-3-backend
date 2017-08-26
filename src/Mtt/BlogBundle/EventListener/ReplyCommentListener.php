<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 21:11
 */

namespace Mtt\BlogBundle\EventListener;

use Mtt\BlogBundle\Event\ReplyCommentEvent;
use Swift_Mailer;
use Swift_Message;
use Twig_Environment;

class ReplyCommentListener
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @param Swift_Mailer $mailer
     * @param Twig_Environment $twig
     */
    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param ReplyCommentEvent $event
     */
    public function onReply(ReplyCommentEvent $event)
    {
        $comment = $event->getComment();
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

                $template = $this->twig->load('MttBlogBundle:Mails:replyComment.html.twig');

                $message = Swift_Message::newInstance()
                    ->setSubject('Ответ на комментарий')
                    ->setFrom('test@example.org')
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
