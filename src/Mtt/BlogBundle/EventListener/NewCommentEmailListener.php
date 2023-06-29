<?php

namespace Mtt\BlogBundle\EventListener;

use Mtt\BlogBundle\Event\CommentEvent;
use Mtt\BlogBundle\Service\Mailer;
use Mtt\UserBundle\Entity\Repository\UserRepository;
use Xelbot\Telegram\Robot;

class NewCommentEmailListener
{
    private Mailer $mailer;

    private UserRepository $repository;

    private Robot $bot;

    /**
     * @param Mailer $mailer
     * @param UserRepository $repository
     * @param Robot $bot
     */
    public function __construct(Mailer $mailer, UserRepository $repository, Robot $bot)
    {
        $this->repository = $repository;
        $this->mailer = $mailer;
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
            $this->bot->sendMessage('new comment email error: ' . $e->getMessage());
        }
    }
}
