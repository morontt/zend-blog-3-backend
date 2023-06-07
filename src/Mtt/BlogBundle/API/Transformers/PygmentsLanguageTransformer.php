<?php

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\DTO\PygmentsLanguageDTO;
use Mtt\BlogBundle\Entity\PygmentsLanguage;

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
