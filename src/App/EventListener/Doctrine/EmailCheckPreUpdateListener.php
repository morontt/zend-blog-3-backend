<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\Interfaces\EmailCheckInterface;
use App\Utils\VerifyEmail;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::preUpdate)]
class EmailCheckPreUpdateListener
{
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof EmailCheckInterface) {
            if ($args->hasChangedField('email')) {
                if ($entity->getEmail()) {
                    $entity
                        ->setFakeEmail(!VerifyEmail::isValid($entity->getEmail()))
                        ->setEmailCheck(new DateTime())
                    ;
                } else {
                    $entity
                        ->setFakeEmail(null)
                        ->setEmailCheck(null)
                    ;
                }

                /** @var \Doctrine\ORM\EntityManagerInterface $em */
                $em = $args->getObjectManager();

                $meta = $em->getClassMetadata(get_class($entity));
                $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
            }
        }
    }
}
