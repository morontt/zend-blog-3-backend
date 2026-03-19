<?php

declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use App\Entity\User;
use App\Utils\VerifyEmail;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class UserEmailCheckSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof User) {
            if ($args->hasChangedField('email')) {
                $result = VerifyEmail::isValid($entity->getEmail());
                $entity
                    ->setFakeEmail(!$result)
                    ->setEmailCheck(new DateTime())
                ;

                /** @var \Doctrine\ORM\EntityManagerInterface $em */
                $em = $args->getObjectManager();

                $meta = $em->getClassMetadata(User::class);
                $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
            }
        }
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof User) {
            $result = VerifyEmail::isValid($entity->getEmail());
            $entity
                ->setFakeEmail(!$result)
                ->setEmailCheck(new DateTime())
            ;
        }
    }
}
