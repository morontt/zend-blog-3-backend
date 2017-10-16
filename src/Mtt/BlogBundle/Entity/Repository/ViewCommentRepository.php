<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\ViewComment;

class ViewCommentRepository extends BaseRepository
{
    /**
     * @param Post $post
     *
     * @return ViewComment[]
     */
    public function getCommentsByPost(Post $post): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->where($qb->expr()->eq('c.post', ':post'))
            ->setParameter('post', $post->getId())
            ->orderBy('c.timeCreated')
        ;

        return $qb->getQuery()->getResult();
    }
}
