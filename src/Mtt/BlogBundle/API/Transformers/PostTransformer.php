<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 19:36
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Post;

class PostTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'Category',
    ];

    /**
     * @param Post $item
     * @return array
     */
    public function transform(Post $item)
    {
        $data = [
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'url' => $item->getUrl(),
            'category' => $item->getCategory()->getId(),
            'hidden' => $item->isHide(),
            'text' => $item->getText(),
            'description' => $item->getDescription(),
            'time_created' => $this->dateTimeToISO($item->getTimeCreated()),
        ];

        return $data;
    }

    /**
     * @param Post $entity
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCategory(Post $entity)
    {
        $items = [$entity->getCategory()];

        return $this->collection($items, new CategoryTransformer, 'categories');
    }
}
