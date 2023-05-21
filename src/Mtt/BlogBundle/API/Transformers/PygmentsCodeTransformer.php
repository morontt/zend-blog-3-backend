<?php

namespace Mtt\BlogBundle\API\Transformers;

use League\Fractal\Resource\Collection;
use Mtt\BlogBundle\Entity\PygmentsCode;

class PygmentsCodeTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'language',
    ];

    /**
     * @param PygmentsCode $item
     *
     * @return array
     */
    public function transform(PygmentsCode $item)
    {
        $languageId = null;
        if ($item->getLanguage()) {
            $languageId = $item->getLanguage()->getId();
        }

        return [
            'id' => $item->getId(),
            'code' => $item->getSourceCode(),
            'html' => $item->getSourceHtml(),
            'language' => $languageId,
            'languageId' => $languageId,
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
        ];
    }

    /**
     * @param PygmentsCode $entity
     * @param array $data
     */
    public static function reverseTransform(PygmentsCode $entity, array $data)
    {
        $entity->setSourceCode($data['code']);
    }

    /**
     * @param PygmentsCode $entity
     *
     * @return Collection
     */
    public function includeLanguage(PygmentsCode $entity): Collection
    {
        $items = [];
        if ($entity->getLanguage()) {
            $items = [$entity->getLanguage()];
        }

        return $this->collection($items, new PygmentsLanguageTransformer(), 'pygmentsLanguages');
    }
}
