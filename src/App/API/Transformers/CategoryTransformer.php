<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 15:09
 */

namespace App\API\Transformers;

use App\DTO\CategoryDTO;
use App\Entity\Category;
use App\Entity\CategoryInterface;
use App\Utils\RuTransform;

class CategoryTransformer extends BaseTransformer
{
    /**
     * @param CategoryInterface $item
     *
     * @return array<string, mixed>
     */
    public function transform(CategoryInterface $item): array
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
            'depth' => $item->getNestedSet()->getDepth(),
            'postsCount' => $item->getPostsCount(),
        ];
    }

    public static function reverseTransform(Category $entity, CategoryDTO $data): void
    {
        $entity->setName($data['name']);

        if (!empty($data['url'])) {
            $entity->setUrl($data['url']);
        } else {
            $entity->setUrl(RuTransform::ruTransform($data['name']));
        }
    }
}
