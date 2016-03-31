<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 19:36
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Tag;
use Mtt\BlogBundle\Utils\RuTransform;

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
            'categoryId' => $item->getCategory()->getId(),
            'hidden' => $item->isHide(),
            'text' => $item->getText(),
            'description' => $item->getDescription(),
            'tagsString' => implode(
                ', ',
                $item->getTags()->map(
                    function (Tag $tag) {
                        return $tag->getName();
                    }
                )->toArray()
            ),
            'timeCreated' => $this->dateTimeToISO($item->getTimeCreated()),
        ];

        return $data;
    }

    /**
     * @param Post $entity
     * @param array $data
     */
    public static function reverseTransform(Post $entity, array $data)
    {
        if (empty($data['title'])) {
            $data['title'] = 'no subject';
        }

        $entity
            ->setTitle($data['title'])
            ->setHide($data['hidden'])
            ->setText($data['text'])
            ->setDescription($data['description'])
        ;

        if ($data['url']) {
            $entity->setUrl($data['url']);
        } else {
            $entity->setUrl(RuTransform::ruTransform($data['title']));
        }
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
