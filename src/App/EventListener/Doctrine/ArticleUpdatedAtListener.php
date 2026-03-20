<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Entity\Post;
use DateTime;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ArticleUpdatedAtListener
{
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof Post) {
            $changed = false;
            if ($args->hasChangedField('text')) {
                $entity->setUpdatedAt(new DateTime());
                $changed = true;
            }

            if ($args->hasChangedField('timeCreated') || $args->hasChangedField('forceCreatedAt')) {
                $entity->recalculateSortField();
                $changed = true;
            }

            if ($changed) {
                /** @var \Doctrine\ORM\EntityManagerInterface $em */
                $em = $args->getObjectManager();

                $meta = $em->getClassMetadata(Post::class);
                $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
            }
        }
    }
}
