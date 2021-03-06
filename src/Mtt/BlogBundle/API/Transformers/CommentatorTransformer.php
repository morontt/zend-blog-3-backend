<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 23.11.14
 * Time: 12:22
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\CommentatorInterface;

class CommentatorTransformer extends BaseTransformer
{
    /**
     * @param CommentatorInterface $item
     *
     * @return array
     */
    public function transform(CommentatorInterface $item)
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'email' => $item->getEmail(),
            'website' => $item->getWebsite(),
            'disqusId' => $item->getDisqusId(),
            'emailHash' => $item->getAvatarHash(),
        ];
    }

    /**
     * @param Commentator $entity
     * @param array $data
     */
    public static function reverseTransform(Commentator $entity, array $data)
    {
        $entity
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setWebsite($data['website'])
        ;
    }
}
