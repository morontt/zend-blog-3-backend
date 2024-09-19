<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 04.04.16
 * Time: 22:50
 */

namespace App\API\Transformers;

use League\Fractal\Resource\Collection;
use App\Entity\MediaFile;
use App\Model\Image;
use App\Service\ImageManager;

class MediaFileTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'post',
    ];

    /**
     * @param Image $item
     *
     * @return array
     */
    public function transform(Image $item)
    {
        $post = $item->getPost();
        $postId = $post ? $post->getId() : null;

        return [
            'id' => $item->getId(),
            'path' => ImageManager::getImageBasePath() . '/' . $item->getPath(),
            'preview' => ImageManager::getImageBasePath() . '/' . $item->getPreview('admin_list'),
            'originalFilename' => $item->getOriginalFileName(),
            'fileSize' => $item->getFileSize(),
            'description' => $item->getDescription(),
            'timeCreated' => $this->dateTimeToISO($item->getTimeCreated()),
            'lastUpdate' => $this->dateTimeToISO($item->getLastUpdate()),
            'post' => $postId,
            'postId' => $postId,
            'defaultImage' => $item->isDefaultImage(),
            'width' => $item->getWidth(),
            'height' => $item->getHeight(),
        ];
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
     * @param Image $item
     *
     * @return Collection
     */
    public function includePost(Image $item): Collection
    {
        $items = [];
        $post = $item->getPost();
        if ($post) {
            $items = [$post];
        }

        return $this->collection($items, new PostTransformer(), 'posts');
    }
}
