<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\PygmentsLanguage;

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
     * @return array
     */
    public function getNamesArray(): array
    {
        $qb = $this->createQueryBuilder('lang')
            ->select('lang.id', 'lang.name')
            ->orderBy('lang.name', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }
}
