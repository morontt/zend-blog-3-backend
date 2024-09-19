<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 15:09
 */

namespace App\API\Transformers;

use App\DTO\CategoryDTO;
use App\Entity\Category;
use App\Utils\RuTransform;

class CategoryTransformer extends BaseTransformer
{
    /**
     * @param Category $item
     *
     * @return array
     */
    public function transform(Category $item)
    {
        $parentId = null;
        $parent = $item->getParent();
        if ($parent) {
            $parentId = $parent->getId();
        }

        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'url' => $item->getUrl(),
            'parent' => $parentId,
            'parentId' => $parentId,
        ];
    }

    /**
     * @param Category $entity
     * @param CategoryDTO $data
     */
    public static function reverseTransform(Category $entity, CategoryDTO $data)
    {
        $entity->setName($data['name']);

        if (!empty($data['url'])) {
            $entity->setUrl($data['url']);
        } else {
            $entity->setUrl(RuTransform::ruTransform($data['name']));
        }
    }
}
