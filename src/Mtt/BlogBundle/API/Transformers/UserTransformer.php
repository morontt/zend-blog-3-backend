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
            'email' => $item->getEmail(),
            'role' => $item->getUserType(),
        ];
    }
}
