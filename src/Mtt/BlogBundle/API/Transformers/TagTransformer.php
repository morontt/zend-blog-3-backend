<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 18:32
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Tag;

class TagTransformer extends BaseTransformer
{
    /**
     * @param Tag $item
     * @return array
     */
    public function transform(Tag $item)
    {
        $data = [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'url' => $item->getUrl(),
        ];

        return $data;
    }
}
