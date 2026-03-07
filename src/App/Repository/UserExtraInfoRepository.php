<?php

namespace App\Repository;

use App\Entity\UserExtraInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserExtraInfo|null findOneBy(array<string, mixed> $criteria)
 *
 * @extends ServiceEntityRepository<UserExtraInfo>
 */
class UserExtraInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserExtraInfo::class);
    }
}
