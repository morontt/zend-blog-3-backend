<?php

/**
 * User: morontt
 * Date: 11.05.2025
 * Time: 20:24
 */

namespace App\EventListener\Comment;

use App\Event\UpdateCommentatorEvent;
use App\Repository\CommentRepository;

class UpdateCommentsListener
{
    private CommentRepository $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateCommentatorEvent $event)
    {
        $this->repository->updateCommentsByCommentator($event->getCommentator()->getId());
    }
}
