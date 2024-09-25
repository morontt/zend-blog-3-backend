<?php

namespace App\EventListener\Doctrine;

use App\Entity\MediaFile;
use App\Model\Image;
use App\Service\PictureTagBuilder;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class MediaFilePictureListener
{
    private PictureTagBuilder $ptb;

    public function __construct(PictureTagBuilder $ptb)
    {
        $this->ptb = $ptb;
    }

    /**
     * @param PreUpdateEventArgs $args
     *
     * @throws \JsonException
     *
     * @return void
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();
        if (($entity instanceof MediaFile)
            && $entity->isImage()
            && $entity->isDefaultImage()
            && !$args->hasChangedField('pictureTag')
            && ($args->hasChangedField('defaultImage') || $args->hasChangedField('description'))
        ) {
            $picture = $this->ptb->featuredPictureTag($entity);
            $entity->setPictureTag($picture);

            $srcSet = (new Image($entity))->getSrcSet();
            $srcSetData = $srcSet->toArray();
            if (!empty($srcSetData)) {
                $entity->setSrcSet(json_encode($srcSetData, JSON_THROW_ON_ERROR));
            }

            $em = $args->getEntityManager();
            $meta = $em->getClassMetadata(MediaFile::class);
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }
}
