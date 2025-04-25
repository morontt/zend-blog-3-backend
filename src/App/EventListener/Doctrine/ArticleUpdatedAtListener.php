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
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        if (($entity instanceof Post) && $args->hasChangedField('text')) {
            $entity->setUpdatedAt(new DateTime());

            $em = $args->getEntityManager();

            $meta = $em->getClassMetadata(Post::class);
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }
}
