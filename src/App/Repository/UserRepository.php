<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;

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

    public function getListQuery(): Query
    {
        return $this
            ->createQueryBuilder('e')
            ->orderBy('e.id', 'ASC')
            ->getQuery();
    }

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
            throw new LogicException('Blog without admin :(');
        }

        return $user;
    }

    public function getByRandomName(string $random): ?User
    {
        $qb = $this->createQueryBuilder('u');
        $qb
            ->where($qb->expr()->eq('u.username', ':username'))
            ->orWhere($qb->expr()->eq('u.email', ':email'))
            ->setParameter('username', $random)
            ->setParameter('email', User::fakeEmail($random))
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
