<?php

namespace Mtt\BlogBundle\API\Transformers;

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
     * @param array $data
     */
    public static function reverseTransform(PygmentsLanguage $entity, array $data)
    {
        $entity
            ->setName($data['name'])
            ->setLexer($data['lexer'])
        ;
    }
}
