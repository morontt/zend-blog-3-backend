<?php

namespace Mtt\BlogBundle\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mtt\BlogBundle\DTO\CommentatorDTO;
use Mtt\BlogBundle\Entity\Commentator;

/**
 * CommentatorRepository
 *
 * @method Commentator|null findOneByDisqusId($id)
 */
class CommentatorRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentator::class);
    }

    public function findOrCreate(CommentatorDTO $commentator): Commentator
    {
        $result = $this->getByCommentatorData($commentator);
        if (!$result) {
            $result = new Commentator();
            $result
                ->setName($commentator->name)
                ->setEmail($commentator->email)
                ->setWebsite($commentator->getNormalizedURL())
            ;

            $this->getEntityManager()->persist($result);
        }

        return $result;
    }

    public function getByCommentatorData(CommentatorDTO $commentator): ?Commentator
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where($qb->expr()->eq('c.name', ':name'))
            ->setParameter('name', $commentator->name)
        ;

        if ($commentator->email) {
            $qb
                ->andWhere($qb->expr()->eq('c.email', ':email'))
                ->setParameter('email', $commentator->email)
            ;
        } else {
            $qb->andWhere($qb->expr()->isNull('c.email'));
        }

        if ($commentator->website) {
            $qb
                ->andWhere($qb->expr()->eq('c.website', ':website'))
                ->setParameter('website', $commentator->getNormalizedURL())
            ;
        } else {
            $qb->andWhere($qb->expr()->isNull('c.website'));
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Commentator[]
     */
    public function getWithUncheckedEmails(): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->andWhere($qb->expr()->isNotNull('c.email'))
            ->andWhere($qb->expr()->isNull('c.emailCheck'))
            ->setMaxResults(20)
        ;

        return $qb->getQuery()->getResult();
    }
}
