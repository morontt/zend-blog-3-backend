<?php

namespace App\EventSubscriber;

use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use App\Event\CommentEvent;
use App\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CountCommentsSubscriber implements EventSubscriberInterface
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
     * @param CommentEvent $event
     */
    public function updateCount(CommentEvent $event)
    {
        $comment = $event->getComment();

        $conn = $this->em->getConnection();

        try {
            $stmt = $conn->prepare('CALL update_comments_count(:postId)');
            $stmt->bindValue('postId', $comment->getPost()->getId());
            $stmt->executeQuery();
        } catch (DriverException|DBALException $e) {
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::REPLY_COMMENT => ['updateCount', 10],
            Events::DELETE_COMMENT => ['updateCount', 10],
        ];
    }
}
