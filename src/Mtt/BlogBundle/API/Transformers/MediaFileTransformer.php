<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 04.04.16
 * Time: 22:50
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\MediaFile;

class MediaFileTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'post',
    ];

    /**
     * @param MediaFile $item
     *
     * @return array
     */
    public function transform(MediaFile $item)
    {
        $post = $item->getPost();
        $postId = $post ? $post->getId() : null;

        $data = [
            'id' => $item->getId(),
            'path' => $item->getPath(),
            'originalFilename' => pathinfo($item->getPath(), PATHINFO_BASENAME),
            'fileSize' => $item->getFileSize(),
            'description' => $item->getDescription(),
            'timeCreated' => $this->dateTimeToISO($item->getTimeCreated()),
            'lastUpdate' => $this->dateTimeToISO($item->getLastUpdate()),
            'post' => $postId,
            'postId' => $postId,
            'defaultImage' => $item->isDefaultImage(),
        ];

        return $data;
    }

    /**
     * @param MediaFile $entity
     * @param array $data
     */
    public static function reverseTransform(MediaFile $entity, array $data)
    {
        $entity
            ->setDescription($data['description'])
            ->setDefaultImage($data['defaultImage'])
        ;
    }

    /**
     * @param MediaFile $item
     *
     * @return \League\Fractal\Resource\Collection|null
     */
    public function includePost(MediaFile $item)
    {
        $post = $item->getPost();

        return $post ? $this->collection([$post], new PostTransformer(), 'posts') : null;
    }
}
