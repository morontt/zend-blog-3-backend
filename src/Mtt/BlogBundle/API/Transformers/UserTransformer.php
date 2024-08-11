<?php

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\UserBundle\Entity\User;

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
}
