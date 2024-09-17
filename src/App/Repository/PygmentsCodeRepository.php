<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\PygmentsCode;

/**
 * @method PygmentsCode|null find($id, $lockMode = null, $lockVersion = null)
 */
class PygmentsCodeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PygmentsCode::class);
    }

    public function getListQuery(): Query
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->select('e', 'l')
            ->leftJoin('e.language', 'l')
            ->orderBy('e.id', 'DESC')
        ;

        return $qb->getQuery();
    }
}
