<?php

namespace App\EventListener;

use App\Repository\CommentRepository;
use App\Event\UserEvent;

class UserCommentsListener
{
    private CommentRepository $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function onUpdate(UserEvent $event)
    {
        $this->repository->updateUserComments($event->getUser()->getId());
    }
}
