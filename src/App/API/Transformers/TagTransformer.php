<?php

declare(strict_types=1);

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
     * @return array<string, mixed>
     */
    public function transform(Tag $item): array
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'url' => $item->getUrl(),
        ];
    }

    public static function reverseTransform(Tag $entity, TagDTO $data): void
    {
        $entity->setName($data['name']);

        if (!empty($data['url'])) {
            $entity->setUrl($data['url']);
        } else {
            $entity->setUrl(RuTransform::ruTransform($data['name']));
        }
    }
}
