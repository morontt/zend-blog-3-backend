<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\BlogBundle\Entity\TrackingAgent;

/**
 * TrackingAgentRepository
 *
 * @method TrackingAgent|null findOneByHash($hash)
 */
class TrackingAgentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackingAgent::class);
    }
}
