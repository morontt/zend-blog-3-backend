<?php

declare(strict_types=1);

namespace App\API\Transformers;

use App\DTO\PygmentsCodeDTO;
use App\Entity\PygmentsCode;
use League\Fractal\Resource\ResourceInterface;

class PygmentsCodeTransformer extends BaseTransformer
{
    /**
     * @var string[]
     */
    protected array $availableIncludes = [
        'language',
    ];

    /**
     * @param PygmentsCode $item
     *
     * @return array<string, mixed>
     */
    public function transform(PygmentsCode $item): array
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

    public static function reverseTransform(PygmentsCode $entity, PygmentsCodeDTO $data): void
    {
        $entity->setSourceCode($data['code']);
    }

    /**
     * @param PygmentsCode $entity
     *
     * @return ResourceInterface
     */
    public function includeLanguage(PygmentsCode $entity): ResourceInterface
    {
        $items = [];
        if ($entity->getLanguage()) {
            $items = [$entity->getLanguage()];
        }

        return $this->collection($items, new PygmentsLanguageTransformer(), 'pygmentsLanguages');
    }
}
