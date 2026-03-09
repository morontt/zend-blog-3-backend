<?php

namespace App\EventListener\Comment;

use App\Event\CommentEvent;
use App\Utils\RottenLink;
use App\Utils\VerifyEmail;
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
        if (!$commentator || (!$commentator->getEmail() && !$commentator->getWebsite())) {
            return;
        }

        if ($commentator->getEmail() && !$commentator->getEmailCheck()) {
            $result = VerifyEmail::isValid($commentator->getEmail());
            $commentator
                ->setFakeEmail(!$result)
                ->setEmailCheck(new DateTime())
            ;
        }

        if ($commentator->getWebsite()) {
            $result = RottenLink::doesWork($commentator->getWebsite());
            $commentator
                ->setRottenLink(!$result)
                ->setRottenCheck(new DateTime())
            ;
        }

        $this->em->flush();
    }
}
