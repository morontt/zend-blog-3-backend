<?php

namespace Mtt\BlogBundle\EventListener;

use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Event\ReplyCommentEvent;

class CountCommentsListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param ReplyCommentEvent $event
     */
    public function onReply(ReplyCommentEvent $event)
    {
        $comment = $event->getComment();

        $conn = $this->em->getConnection();

        try {
            $stmt = $conn->prepare('CALL update_comments_count(:postId)');
            $stmt->bindValue('postId', (int)$comment->getPost()->getId());
            $stmt->executeQuery();
        } catch (DriverException|DBALException $e) {
        }
    }
}
