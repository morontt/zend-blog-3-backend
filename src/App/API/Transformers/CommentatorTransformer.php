<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 23.11.14
 * Time: 12:22
 */

namespace App\API\Transformers;

use App\Entity\Commentator;
use App\Entity\CommentatorInterface;
use App\Entity\User;

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
            'imageHash' => $item->getAvatarHash(),
            'isMale' => $item->getGender() === User::MALE,
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
            ->setGender($data['isMale'] ? User::MALE : User::FEMALE)
        ;
    }
}
