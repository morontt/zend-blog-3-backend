<?php

namespace App\Repository;

use App\Entity\PygmentsLanguage;
use App\Repository\Traits\ListQueryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PygmentsLanguage>
 */
class PygmentsLanguageRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PygmentsLanguage::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function getNamesArray(): array
    {
        $qb = $this->createQueryBuilder('lang')
            ->select('lang.id', 'lang.name')
            ->orderBy('lang.name', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }
}
