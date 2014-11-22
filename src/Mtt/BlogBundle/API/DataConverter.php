<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 12:33
 */

namespace Mtt\BlogBundle\API;


class DataConverter
{
    public function getCategoryArray(array $categories)
    {
        $data = ['categories' => []];

        foreach ($categories as $item) {
            $parentId = null;
            $parent = $item->getParent();
            if ($parent) {
                $parentId = $parent->getId();
            }

            $data['categories'][] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'url' => $item->getUrl(),
                'parent_id' => $parentId,
            ];
        }

        return $data;
    }
}
