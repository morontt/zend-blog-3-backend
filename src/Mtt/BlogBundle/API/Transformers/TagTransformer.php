<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 18:32
 */

namespace Mtt\BlogBundle\API\Transformers;

use League\Fractal\TransformerAbstract;
use Mtt\BlogBundle\Entity\Tag;

class TagTransformer extends TransformerAbstract
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
