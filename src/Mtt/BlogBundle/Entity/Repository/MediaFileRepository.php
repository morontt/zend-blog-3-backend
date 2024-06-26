<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\BlogBundle\Entity\MediaFile;

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
    public function getNotBackuped()
    {
        $qb = $this->createQueryBuilder('m');

        $qb
            ->where($qb->expr()->eq('m.backuped', ':backuped'))
            ->setParameter('backuped', false)
            ->setMaxResults(30)
        ;

        return $qb->getQuery()->getResult();
    }
}
