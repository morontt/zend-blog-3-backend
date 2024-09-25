<?php

namespace App\EventListener;

use App\Event\UserEvent;
use App\Repository\CommentRepository;

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
