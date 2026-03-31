<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\CommentEvent;
use App\Event\DeleteCommentEvent;
use App\LogTrait;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CountCommentsSubscriber implements EventSubscriberInterface
{
    use LogTrait;

    public function __construct(
        private EntityManagerInterface $em,
        LoggerInterface $logger,
    ) {
        $this->setLogger($logger);
    }

    /**
     * @param CommentEvent $event
     */
    public function updateCount(CommentEvent $event): void
    {
        $connection = $this->em->getConnection();
        try {
            $stmt = $connection->prepare('CALL update_comments_count(:postId)');
            $stmt->bindValue('postId', $event->getComment()->getPost()->getId());
            $stmt->executeQuery();
        } catch (DriverException|DBALException $e) {
            $this->error('update comment count error', ['exception' => $e]);
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
