<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Mtt\BlogBundle\Entity\Comment;

/**
 * CommentRepository
 *
 * @method Comment|null findOneByDisqusId($id)
 */
class CommentRepository extends BaseRepository
{
    /**
     * @return \Mtt\BlogBundle\Entity\Comment|null
     */
    public function getLastDisqusComment()
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->where($qb->expr()->isNotNull('c.disqusId'))
            ->orderBy('c.timeCreated', 'DESC')
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
