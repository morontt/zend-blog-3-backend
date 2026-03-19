<?php

declare(strict_types=1);

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
     * @return array<string, mixed>
     */
    public function transform(CommentatorInterface $item): array
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'email' => $item->getEmail(),
            'website' => $item->getWebsite(),
            'imageHash' => $item->getAvatarHash(),
            'isMale' => $item->getGender() === User::MALE,
            'isValidEmail' => $item->isValidEmail(),
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function reverseTransform(Commentator $entity, array $data): void
    {
        $entity
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setWebsite($data['website'])
            ->setGender($data['isMale'] ? User::MALE : User::FEMALE)
        ;
    }
}
