<?php

namespace App\EventListener;

use App\Event\CommentEvent;
use App\Service\Mailer;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Xelbot\Telegram\Robot;

class NewCommentEmailListener
{
    private Mailer $mailer;

    private UserRepository $repository;

    private LoggerInterface $logger;

    private Robot $bot;

    /**
     * @param Mailer $mailer
     * @param UserRepository $repository
     * @param Robot $bot
     */
    public function __construct(Mailer $mailer, UserRepository $repository, LoggerInterface $logger, Robot $bot)
    {
        $this->repository = $repository;
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->bot = $bot;
    }

    /**
     * @param CommentEvent $event
     */
    public function onReply(CommentEvent $event)
    {
        try {
            $admin = $this->repository->getAdmin();
            if (!$admin->getEmail()) {
                return;
            }

            $comment = $event->getComment();

            $user = $comment->getUser();
            if ($user && $user->getId() === $admin->getId()) {
                return;
            }

            $parent = $comment->getParent();
            if ($parent) {
                $user = $parent->getUser();
                if ($user && $user->getId() === $admin->getId()) {
                    return;
                }
            }

            $this->mailer->newComment($comment, $admin->getEmail());
        } catch (\Throwable $e) {
            $this->logger->error('new comment email error', ['exception' => $e]);
            $this->bot->sendMessage('new comment email error: ' . $e->getMessage());
        }
    }
}
