<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\ViewComment;

class ViewCommentRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewComment::class);
    }

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
