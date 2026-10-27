<?php

namespace App\Repository\Traits;

use Doctrine\ORM\Query;

trait ListQueryTrait
{
    /**
     * @param bool $nameBased
     *
     * @phpstan-ignore missingType.generics
     */
    public function getListQuery(bool $nameBased = false): Query
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->createQueryBuilder('e');

        if ($nameBased) {
            $qb->orderBy('e.name', 'ASC');
        } else {
            $qb->orderBy('e.id', 'DESC');
        }

        /* @phpstan-ignore doctrine.dql */
        return $qb->getQuery();
    }
}
