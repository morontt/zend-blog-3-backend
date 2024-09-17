<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

trait ListQueryTrait
{
    /**
     * @param bool $nameBased
     *
     * @return Query
     */
    public function getListQuery(bool $nameBased = false): Query
    {
        /* @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('e');

        if ($nameBased) {
            $qb->orderBy('e.name', 'ASC');
        } else {
            $qb->orderBy('e.id', 'DESC');
        }

        return $qb->getQuery();
    }
}
