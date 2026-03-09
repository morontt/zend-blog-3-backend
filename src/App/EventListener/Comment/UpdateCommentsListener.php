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
    public function __construct(private CommentRepository $repository)
    {
    }

    public function __invoke(UpdateCommentatorEvent $event): void
    {
        $this->repository->updateCommentsByCommentator($event->getCommentator()->getId());
    }
}
