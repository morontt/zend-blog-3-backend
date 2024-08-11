<?php

namespace Mtt\UserBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\UserBundle\Entity\UserExtraInfo;

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
