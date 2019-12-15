<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\BlogBundle\Entity\SystemParameters;

/**
 * SystemParametersRepository
 *
 * @method SystemParameters|null findOneByOptionKey($key)
 */
class SystemParametersRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SystemParameters::class);
    }
}
