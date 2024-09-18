<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\SystemParameters;

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
