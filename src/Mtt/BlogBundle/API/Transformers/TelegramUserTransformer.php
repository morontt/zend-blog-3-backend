<?php

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\TelegramUser;

class TelegramUserTransformer extends BaseTransformer
{
    public function transform(TelegramUser $item): array
    {
        return [
            'id' => $item->getId(),
            'firstName' => $item->getFirstName(),
            'lastName' => $item->getLastName(),
            'username' => $item->getUsername(),
            'bot' => $item->isBot(),
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
        ];
    }
}
