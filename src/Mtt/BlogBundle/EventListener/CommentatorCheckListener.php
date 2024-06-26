<?php

namespace Mtt\BlogBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Event\CommentEvent;
use Mtt\BlogBundle\Utils\RottenLink;
use Mtt\BlogBundle\Utils\VerifyEmail;

class CommentatorCheckListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onReply(CommentEvent $event)
    {
        $commentator = $event->getComment()->getCommentator();
        if (!$commentator || (!$commentator->getEmail() && !$commentator->getWebsite())) {
            return;
        }

        if ($commentator->getEmail() && !$commentator->getEmailCheck()) {
            $result = VerifyEmail::isValid($commentator->getEmail());
            $commentator
                ->setFakeEmail(!$result)
                ->setEmailCheck(new \DateTime())
            ;
        }

        if ($commentator->getWebsite()) {
            $result = RottenLink::doesWork($commentator->getWebsite());
            $commentator
                ->setRottenLink(!$result)
                ->setRottenCheck(new \DateTime())
            ;
        }

        $this->em->flush();
    }
}
