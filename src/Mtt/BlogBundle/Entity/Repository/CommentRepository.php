<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\GeoLocation;

/**
 * CommentRepository
 *
 * @method Comment|null findOneByDisqusId($id)
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return array
     */
    public function getUncheckedIps()
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->select('c.ipAddress')
            ->where($qb->expr()->isNull('c.geoLocation'))
            ->andWhere($qb->expr()->isNotNull('c.ipAddress'))
            ->groupBy('c.ipAddress')
            ->setMaxResults(20)
        ;

        return array_column($qb->getQuery()->getArrayResult(), 'ipAddress');
    }

    /**
     * @param GeoLocation $location
     * @param string $ip
     *
     * @return mixed
     */
    public function updateLocation(GeoLocation $location, $ip)
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->update()
            ->set('c.geoLocation', ':location')
            ->where($qb->expr()->eq('c.ipAddress', ':ip'))
            ->setParameter('location', $location->getId())
            ->setParameter('ip', $ip)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param int $index
     * @param int $postId
     */
    public function addToTree(int $index, int $postId): void
    {
        $qb0 = $this->createQueryBuilder('c');
        $qb0->update()
            ->set('c.nestedSet.leftKey', 'c.nestedSet.leftKey + 2')
            ->where($qb0->expr()->gte('c.nestedSet.leftKey', ':idx'))
            ->andWhere($qb0->expr()->isNotNull('c.nestedSet.leftKey'))
            ->andWhere($qb0->expr()->eq('c.post', ':postId'))
            ->setParameter('postId', $postId)
            ->setParameter('idx', $index)
            ->getQuery()
            ->execute()
        ;

        $qb1 = $this->createQueryBuilder('c');
        $qb1->update()
            ->set('c.nestedSet.rightKey', 'c.nestedSet.rightKey + 2')
            ->where($qb1->expr()->gte('c.nestedSet.rightKey', ':idx'))
            ->andWhere($qb1->expr()->isNotNull('c.nestedSet.leftKey'))
            ->andWhere($qb1->expr()->eq('c.post', ':postId'))
            ->setParameter('postId', $postId)
            ->setParameter('idx', $index)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @param Comment $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function markAsDeleted(Comment $entity)
    {
        $entity->setDeleted(true);
        $this->_em->flush();
    }
}
