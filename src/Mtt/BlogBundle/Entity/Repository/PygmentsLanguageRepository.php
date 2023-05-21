<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\BlogBundle\Entity\PygmentsLanguage;

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
