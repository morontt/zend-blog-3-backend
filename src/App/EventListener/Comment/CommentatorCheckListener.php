<?php

namespace App\EventListener\Comment;

use App\Event\CommentEvent;
use App\Utils\RottenLink;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CommentatorCheckListener
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(CommentEvent $event): void
    {
        $commentator = $event->getComment()->getCommentator();
        if (!$commentator || !$commentator->getWebsite()) {
            return;
        }

        $commentator
            ->setRottenLink(!RottenLink::doesWork($commentator->getWebsite()))
            ->setRottenCheck(new DateTime())
        ;

        $this->em->flush();
    }
}
