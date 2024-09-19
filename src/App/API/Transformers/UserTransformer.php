<?php

namespace App\API\Transformers;

use App\DTO\UserDTO;
use App\Entity\User;

class UserTransformer extends BaseTransformer
{
    public function transform(User $item): array
    {
        return [
            'id' => $item->getId(),
            'username' => $item->getUsername(),
            'displayName' => $item->getDisplayName(),
            'email' => $item->getEmail(),
            'role' => $item->getUserType(),
            'imageHash' => $item->getAvatarHash(),
            'isMale' => $item->getGender() === User::MALE,
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
        ];
    }

    public static function reverseTransform(User $entity, UserDTO $data)
    {
        $entity
            ->setUsername($data['username'])
            ->setEmail($data['email'])
            ->setUserType($data['role'])
            ->setGender($data['isMale'] ? User::MALE : User::FEMALE)
            ->setDisplayName($data['displayName'])
        ;
    }
}
