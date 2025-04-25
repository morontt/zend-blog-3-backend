<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 18:32
 */

namespace App\API\Transformers;

use App\DTO\TagDTO;
use App\Entity\Tag;
use App\Utils\RuTransform;

class TagTransformer extends BaseTransformer
{
    /**
     * @param Tag $item
     *
     * @return array
     */
    public function transform(Tag $item)
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'url' => $item->getUrl(),
        ];
    }

    /**
     * @param Tag $entity
     * @param TagDTO $data
     */
    public static function reverseTransform(Tag $entity, TagDTO $data)
    {
        $entity->setName($data['name']);

        if (!empty($data['url'])) {
            $entity->setUrl($data['url']);
        } else {
            $entity->setUrl(RuTransform::ruTransform($data['name']));
        }
    }
}
