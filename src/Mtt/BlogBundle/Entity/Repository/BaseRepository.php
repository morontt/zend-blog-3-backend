<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 04.04.16
 * Time: 22:39
 */

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository
{
    /**
     * @param bool $nameBased
     *
     * @return \Doctrine\ORM\Query
     */
    public function getListQuery($nameBased = false)
    {
        $qb = $this->createQueryBuilder('e');

        if ($nameBased) {
            $qb->orderBy('e.name', 'ASC');
        } else {
            $qb->orderBy('e.id', 'DESC');
        }

        return $qb->getQuery();
    }
}
