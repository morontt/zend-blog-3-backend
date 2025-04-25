<?php

namespace App\EventListener\Comment;

use App\Event\CommentEvent;
use App\Repository\GeoLocationRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentGeolocationListener
{
    private EntityManagerInterface $em;

    private GeoLocationRepository $repository;

    /**
     * @param EntityManagerInterface $em
     * @param GeoLocationRepository $repository
     */
    public function __construct(EntityManagerInterface $em, GeoLocationRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function __invoke(CommentEvent $event): void
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
