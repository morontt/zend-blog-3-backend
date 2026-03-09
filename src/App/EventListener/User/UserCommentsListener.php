<?php

namespace App\EventListener\User;

use App\Event\UserEvent;
use App\Repository\CommentRepository;

class UserCommentsListener
{
    public function __construct(private CommentRepository $repository)
    {
    }

    public function __invoke(UserEvent $event): void
    {
        $this->repository->updateUserComments($event->getUser()->getId());
    }
}
