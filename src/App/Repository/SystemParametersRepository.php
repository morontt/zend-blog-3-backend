<?php

namespace App\Repository;

use App\Entity\SystemParameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
