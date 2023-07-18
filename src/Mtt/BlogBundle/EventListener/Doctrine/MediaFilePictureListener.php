<?php

namespace Mtt\BlogBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Model\Image;
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
            $picture = $this->im->featuredPictureTag($entity);
            $entity->setPictureTag($picture);

            $srcSet = (new Image($entity))->getSrcSet();
            $srcSetData = $srcSet->toArray();
            if (!empty($srcSetData)) {
                $entity->setSrcSet(json_encode($srcSetData));
            }

            $em = $args->getEntityManager();
            $meta = $em->getClassMetadata(MediaFile::class);
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }
}
