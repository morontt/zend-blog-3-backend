<?php

namespace App\Repository;

use App\Entity\EmailSubscriptionSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailSubscriptionSettings|null findOneBy(array<string, mixed> $criteria, array<string, string> $orderBy = null)
 *
 * @extends ServiceEntityRepository<EmailSubscriptionSettings>
 */
class EmailSubscriptionSettingsRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailSubscriptionSettings::class);
    }

    public function findOrCreate(string $email, int $type): EmailSubscriptionSettings
    {
        $entity = $this->findOneBy(['email' => $email, 'type' => $type]);
        if (!$entity) {
            $entity = new EmailSubscriptionSettings();
            $entity
                ->setEmail($email)
                ->setType($type)
            ;

            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        }

        return $entity;
    }
}
