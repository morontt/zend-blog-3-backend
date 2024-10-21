<?php

namespace App\Repository;

use App\Entity\MediaFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * MediaFileRepository
 *
 * @method MediaFile|null find($id, $lockMode = null, $lockVersion = null)
 */
class MediaFileRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaFile::class);
    }

    /**
     * @return Query
     */
    public function getListQuery(): Query
    {
        $qb = $this->createQueryBuilder('e');
        $qb
            ->select('e', 'p', 't')
            ->leftJoin('e.post', 'p')
            ->leftJoin('p.tags', 't')
            ->orderBy('e.id', 'DESC')
        ;

        return $qb->getQuery();
    }

    /**
     * @param int $postId
     *
     * @return MediaFile[]
     */
    public function getFilesByPost($postId)
    {
        $qb = $this->createQueryBuilder('m');

        $qb
            ->where($qb->expr()->eq('m.post', ':postId'))
            ->setParameter('postId', $postId)
            ->orderBy('m.id')
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $postId
     *
     * @return int
     */
    public function getCountByPostId($postId)
    {
        $qb = $this->createQueryBuilder('m');

        $qb
            ->select('COUNT(m.id)')
            ->where($qb->expr()->eq('m.post', ':postId'))
            ->setParameter('postId', $postId)
        ;

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return MediaFile[]
     */
    public function getNotBackedUp()
    {
        $qb = $this->createQueryBuilder('m');

        $qb
            ->where(
                $qb->expr()->eq(
                    'm.backedUp',
                    $qb->expr()->literal(false)
                )
            )
            ->setMaxResults(30)
        ;

        return $qb->getQuery()->getResult();
    }
}
