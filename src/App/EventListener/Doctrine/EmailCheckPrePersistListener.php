<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\Interfaces\EmailCheckInterface;
use App\Utils\VerifyEmail;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist)]
class EmailCheckPrePersistListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof EmailCheckInterface) {
            if ($entity->getEmail()) {
                $entity
                    ->setFakeEmail(!VerifyEmail::isValid($entity->getEmail()))
                    ->setEmailCheck(new DateTime())
                ;
            }
        }
    }
}
