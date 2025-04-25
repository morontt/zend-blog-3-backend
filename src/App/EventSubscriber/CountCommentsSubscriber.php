<?php

namespace App\EventSubscriber;

use App\Event\CommentEvent;
use App\Event\DeleteCommentEvent;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CountCommentsSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;

    private LoggerInterface $logger;

    /**
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * @param CommentEvent $event
     */
    public function updateCount(CommentEvent $event): void
    {
        $comment = $event->getComment();

        $conn = $this->em->getConnection();

        try {
            $stmt = $conn->prepare('CALL update_comments_count(:postId)');
            $stmt->bindValue('postId', $comment->getPost()->getId());
            $stmt->executeQuery();
        } catch (DriverException|DBALException $e) {
            $this->logger->error('update comment count error', ['exception' => $e]);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CommentEvent::class => ['updateCount', 10],
            DeleteCommentEvent::class => ['updateCount', 10],
        ];
    }
}
