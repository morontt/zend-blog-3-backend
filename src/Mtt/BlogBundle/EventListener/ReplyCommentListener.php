<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 21:11
 */

namespace Mtt\BlogBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Entity\Comment;
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
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $emailFrom;

    /**
     * @param Swift_Mailer $mailer
     * @param Twig_Environment $twig
     * @param EntityManager $em
     * @param string $emailFrom
     */
    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig, EntityManager $em, string $emailFrom)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->em = $em;
        $this->emailFrom = $emailFrom;
    }

    /**
     * @param ReplyCommentEvent $event
     */
    public function onReply(ReplyCommentEvent $event)
    {
        $comment = $event->getComment();
        $this->sendEmail($comment);

        $conn = $this->em->getConnection();

        $stmt = $conn->prepare('CALL update_comments_count(:postId)');
        $stmt->bindValue('postId', (int)$comment->getPost()->getId());
        $stmt->execute();
    }

    /**
     * @param Comment $comment
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
