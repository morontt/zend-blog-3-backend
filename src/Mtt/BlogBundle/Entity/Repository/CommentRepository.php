<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\GeoLocation;

/**
 * CommentRepository
 *
 * @method Comment|null findOneByDisqusId($id)
 */
class CommentRepository extends BaseRepository
{
    /**
     * @return Comment|null
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
}
