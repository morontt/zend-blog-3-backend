<?php

declare(strict_types=1);

namespace App\API\Transformers;

use App\DTO\PygmentsLanguageDTO;
use App\Entity\PygmentsLanguage;

class PygmentsLanguageTransformer extends BaseTransformer
{
    /**
     * @param PygmentsLanguage $item
     *
     * @return array<string, mixed>
     */
    public function transform(PygmentsLanguage $item): array
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'lexer' => $item->getLexer(),
        ];
    }

    public static function reverseTransform(PygmentsLanguage $entity, PygmentsLanguageDTO $data): void
    {
        $entity
            ->setName($data['name'])
            ->setLexer($data['lexer'])
        ;
    }
}
