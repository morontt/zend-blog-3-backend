<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 15:09
 */

namespace Mtt\BlogBundle\API\Transformers;

use League\Fractal\TransformerAbstract;
use Mtt\BlogBundle\Entity\Category;

class CategoryTransformer extends TransformerAbstract
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
}
