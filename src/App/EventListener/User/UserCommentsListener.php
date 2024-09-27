<?php

namespace App\EventListener\User;

use App\Event\UserEvent;
use App\Repository\CommentRepository;

class UserCommentsListener
{
    private CommentRepository $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UserEvent $event)
    {
        $this->repository->updateUserComments($event->getUser()->getId());
    }
}
