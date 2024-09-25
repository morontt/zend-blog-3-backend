<?php

namespace App\Repository;

use App\Entity\UserExtraInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
