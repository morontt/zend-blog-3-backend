<?php

/**
 * User: morontt
 * Date: 01.01.2025
 * Time: 21:38
 */

namespace App\Repository;

use App\Entity\ViewCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class ViewCategoryRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewCategory::class);
    }

    /**
     * @return Query
     */
    public function getListQuery(): Query
    {
        $qb = $this->createQueryBuilder('e');

        return $qb->orderBy('e.nestedSet.leftKey', 'ASC')
            ->getQuery();
    }
}
