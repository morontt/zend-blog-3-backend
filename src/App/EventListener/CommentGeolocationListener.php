<?php

namespace App\EventListener;

use App\Event\CommentEvent;
use App\Repository\GeoLocationRepository;
use Doctrine\ORM\EntityManagerInterface;

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
     * @param GeoLocationRepository $repository
     */
    public function __construct(EntityManagerInterface $em, GeoLocationRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function onReply(CommentEvent $event)
    {
        $comment = $event->getComment();
        if ($comment->getGeoLocation()) {
            return;
        }

        $location = $this->repository->findOrCreateByIpAddress($comment->getIpAddress());
        if ($location) {
            $comment->setGeoLocation($location);
            $this->em->flush();
        }
    }
}
