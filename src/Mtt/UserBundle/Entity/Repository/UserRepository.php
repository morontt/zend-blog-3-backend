<?php

namespace Mtt\UserBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\UserBundle\Entity\User;

/**
 * UserRepository
 *
 * @method User|null findOneByUsername($username)
 * @method User|null findOneBy(array $criteria)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User
     */
    public function getAdmin(): User
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->andWhere($qb->expr()->eq('u.userType', $qb->expr()->literal('admin')))
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(1)
        ;

        $user = $qb->getQuery()->getOneOrNullResult();
        if (!$user) {
            throw new \LogicException('Blog without admin :(');
        }

        return $user;
    }
}
