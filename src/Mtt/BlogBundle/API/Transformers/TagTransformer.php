<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 18:32
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Tag;
use Mtt\BlogBundle\Utils\RuTransform;

class TagTransformer extends BaseTransformer
{
    /**
     * @param Tag $item
     *
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

    /**
     * @param Tag $entity
     * @param array $data
     */
    public static function reverseTransform(Tag $entity, array $data)
    {
        $entity->setName($data['name']);

        if (!empty($data['url'])) {
            $entity->setUrl($data['url']);
        } else {
            $entity->setUrl(RuTransform::ruTransform($data['name']));
        }
    }
}
