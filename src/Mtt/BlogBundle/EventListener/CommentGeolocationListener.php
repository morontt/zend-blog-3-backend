<?php

namespace Mtt\BlogBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Entity\Repository\GeoLocationRepository;
use Mtt\BlogBundle\Event\CommentEvent;

class CommentGeolocationListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var GeoLocationRepository
     */
    private $repository;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, GeoLocationRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function onReply(CommentEvent $event)
    {
        $comment = $event->getComment();

        $location = $this->repository->findOrCreateByIpAddress($comment->getIpAddress());
        if ($location) {
            $comment->setGeoLocation($location);
            $this->em->flush();
        }
    }
}
