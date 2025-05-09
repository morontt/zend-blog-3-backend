<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public const ITERATION_STEP = 15;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Query
     */
    public function getListQuery(): Query
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->select('e', 'c', 't')
            ->innerJoin('e.category', 'c')
            ->leftJoin('e.tags', 't')
            ->orderBy('e.id', 'DESC')
        ;

        return $qb->getQuery();
    }

    /**
     * @param int $i
     *
     * @return Post[]
     */
    public function getPostsForIteration($i): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->orderBy('p.id')
            ->setFirstResult($i * self::ITERATION_STEP)
            ->setMaxResults(self::ITERATION_STEP)
        ;

        return $qb->getQuery()->getResult();
    }

    public function increaseViewCounter(int $articleId, int $cnt)
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->update()
            ->set('p.viewsCount', '(p.viewsCount + :cnt)')
            ->where($qb->expr()->eq('p.id', ':id'))
            ->setParameter('id', $articleId)
            ->setParameter('cnt', $cnt)
        ;

        $qb->getQuery()->execute();
    }

    /**
     * @param int $codeId
     *
     * @return Post[]
     */
    public function getPostsByCodeSnippet(int $codeId): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where($qb->expr()->like('p.rawText', ':code'))
            ->setParameter('code', sprintf('%%!<code>%d!%%', $codeId))
        ;

        return $qb->getQuery()->getResult();
    }
}
