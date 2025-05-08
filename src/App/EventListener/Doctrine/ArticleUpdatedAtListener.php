<?php

namespace App\EventListener\Doctrine;

use App\Entity\Post;
use DateTime;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ArticleUpdatedAtListener
{
    /**
     * @param PreUpdateEventArgs $args
     */
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
                $em = $args->getEntityManager();

                $meta = $em->getClassMetadata(Post::class);
                $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
            }
        }
    }
}
