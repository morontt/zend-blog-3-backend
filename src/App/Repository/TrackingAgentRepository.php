<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TrackingAgent;

/**
 * TrackingAgentRepository
 *
 * @method TrackingAgent|null findOneByHash($hash)
 */
class TrackingAgentRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackingAgent::class);
    }
}
