<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\ViewComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
            ->andWhere($qb->expr()->eq('c.deleted', $qb->expr()->literal(false)))
            ->setParameter('post', $post->getId())
            ->orderBy('c.timeCreated')
        ;

        return $qb->getQuery()->getResult();
    }
}
