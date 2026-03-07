<?php

namespace App\Repository;

use App\Entity\PygmentsCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PygmentsCode|null find($id, $lockMode = null, $lockVersion = null)
 *
 * @extends ServiceEntityRepository<PygmentsCode>
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

    /**
     * @return Query<null, PygmentsCode>
     */
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
