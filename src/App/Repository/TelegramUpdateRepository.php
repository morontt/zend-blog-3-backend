<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TelegramUpdate;

/**
 * @method TelegramUpdate|null find($id, $lockMode = null, $lockVersion = null)
 */
class TelegramUpdateRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramUpdate::class);
    }

    /**
     * @param string $from
     * @param string $to
     * @param int $adminId
     *
     * @return array
     */
    public function countNewMessages(string $from, string $to, int $adminId): int
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->select('COUNT(t.id) AS cnt')
            ->innerJoin('t.telegramUser', 'u')
            ->andWhere($qb->expr()->neq('u.userId', ':adminId'))
            ->andWhere($qb->expr()->gte('t.timeCreated', ':from'))
            ->andWhere($qb->expr()->lt('t.timeCreated', ':to'))
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('adminId', $adminId)
        ;

        return (int)$qb->getQuery()->getSingleScalarResult();
    }
}
