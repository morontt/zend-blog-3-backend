<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\BlogBundle\Entity\PygmentsCode;

/**
 * @method PygmentsCode|null find($id, $lockMode = null, $lockVersion = null)
 */
class PygmentsCodeRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PygmentsCode::class);
    }
}
