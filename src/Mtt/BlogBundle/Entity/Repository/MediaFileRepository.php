<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\BlogBundle\Entity\MediaFile;

/**
 * MediaFileRepository
 *
 * @method MediaFile|null find($id, $lockMode = null, $lockVersion = null)
 */
class MediaFileRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaFile::class);
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
