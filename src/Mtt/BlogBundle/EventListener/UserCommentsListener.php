<?php

namespace Mtt\BlogBundle\EventListener;

use Mtt\BlogBundle\Entity\Repository\CommentRepository;
use Mtt\UserBundle\Event\UserEvent;

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
