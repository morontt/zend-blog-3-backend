<?php

/**
 * User: morontt
 * Date: 29.03.2025
 * Time: 18:14
 */

namespace App\Repository;

use App\Entity\ViewTag;
use App\Repository\Traits\ListQueryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ViewTag>
 */
class ViewTagRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewTag::class);
    }
}
