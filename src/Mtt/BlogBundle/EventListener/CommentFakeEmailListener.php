<?php

namespace Mtt\BlogBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Event\CommentEvent;
use Mtt\BlogBundle\Utils\VerifyEmail;

class CommentFakeEmailListener
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
        if (!$commentator || !$commentator->getEmail() || $commentator->getEmailCheck()) {
            return;
        }

        $result = VerifyEmail::check($commentator->getEmail());
        $commentator
            ->setFakeEmail($result)
            ->setEmailCheck(new \DateTime())
        ;

        $this->em->flush();
    }
}
