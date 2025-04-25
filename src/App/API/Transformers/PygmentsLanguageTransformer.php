<?php

namespace App\API\Transformers;

use App\DTO\PygmentsLanguageDTO;
use App\Entity\PygmentsLanguage;

class PygmentsLanguageTransformer extends BaseTransformer
{
    /**
     * @param PygmentsLanguage $item
     *
     * @return array
     */
    public function transform(PygmentsLanguage $item)
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'lexer' => $item->getLexer(),
        ];
    }

    /**
     * @param PygmentsLanguage $entity
     * @param PygmentsLanguageDTO $data
     */
    public static function reverseTransform(PygmentsLanguage $entity, PygmentsLanguageDTO $data)
    {
        $entity
            ->setName($data['name'])
            ->setLexer($data['lexer'])
        ;
    }
}
