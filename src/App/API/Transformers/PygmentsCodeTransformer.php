<?php

namespace App\API\Transformers;

use App\DTO\PygmentsCodeDTO;
use App\Entity\PygmentsCode;
use League\Fractal\Resource\Collection;

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
            'preview' => $item->getSourceHtmlPreview(),
            'language' => $languageId,
            'languageId' => $languageId,
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
        ];
    }

    /**
     * @param PygmentsCode $entity
     * @param PygmentsCodeDTO $data
     */
    public static function reverseTransform(PygmentsCode $entity, PygmentsCodeDTO $data)
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
