<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\UserExtraInfo;

/**
 * @method UserExtraInfo|null findOneBy(array $criteria)
 */
class UserExtraInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserExtraInfo::class);
    }
}
