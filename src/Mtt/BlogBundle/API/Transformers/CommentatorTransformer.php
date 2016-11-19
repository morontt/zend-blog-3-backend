<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 23.11.14
 * Time: 12:22
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Commentator;

class CommentatorTransformer extends BaseTransformer
{
    /**
     * @param Commentator $item
     *
     * @return array
     */
    public function transform(Commentator $item)
    {
        $data = [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'email' => $item->getMail(),
            'website' => $item->getWebsite(),
            'disqusId' => (int)$item->getDisqusId(),
            'emailHash' => $item->getEmailHash(),
        ];

        return $data;
    }

    /**
     * @param Commentator $entity
     * @param array $data
     */
    public static function reverseTransform(Commentator $entity, array $data)
    {
        $entity
            ->setName($data['name'])
            ->setMail($data['email'])
            ->setWebsite($data['website'])
            ->setEmailHash(Commentator::getAvatarHash($data['name'], $data['email'], $data['website']))
        ;
    }
}
