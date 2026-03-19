<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\User;
use App\Utils\VerifyEmail;
use DateTime;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class UserEmailCheckListener
{
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
}
