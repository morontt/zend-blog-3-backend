<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Post;
use App\Entity\ViewComment;

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
