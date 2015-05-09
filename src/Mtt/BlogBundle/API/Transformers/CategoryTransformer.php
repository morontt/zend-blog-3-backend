<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 15:09
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Utils\RuTransform;

class CategoryTransformer extends BaseTransformer
{
    /**
     * @param Category $item
     * @return array
     */
    public function transform(Category $item)
    {
        $parentId = null;
        $parent = $item->getParent();
        if ($parent) {
            $parentId = $parent->getId();
        }

        $data = [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'url' => $item->getUrl(),
            'parent_id' => $parentId,
        ];

        return $data;
    }

    /**
     * @param Category $entity
     * @param array $data
     */
    public static function reverseTransform(Category $entity, array $data)
    {
        $entity->setName($data['name']);

        if ($data['url']) {
            $entity->setUrl($data['url']);
        } else {
            $entity->setUrl(RuTransform::ruTransform($data['name']));
        }
    }
}
