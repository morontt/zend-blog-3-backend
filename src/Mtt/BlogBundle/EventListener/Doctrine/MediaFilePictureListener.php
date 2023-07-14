<?php

namespace Mtt\BlogBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Service\ImageManager;

class MediaFilePictureListener
{
    private ImageManager $im;

    public function __construct(ImageManager $im)
    {
        $this->im = $im;
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        if (($entity instanceof MediaFile)
            && $entity->isImage()
            && $entity->isDefaultImage()
            && $args->hasChangedField('defaultImage')
            && !$args->hasChangedField('pictureTag')
        ) {
            $picture = $this->im->pictureTag($entity, $entity->getDescription(), false);
            $entity->setPictureTag($picture);

            $em = $args->getEntityManager();
            $meta = $em->getClassMetadata(MediaFile::class);
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }
}
