<?php

namespace App\API\Transformers;

use App\Entity\TelegramUser;

class TelegramUserTransformer extends BaseTransformer
{
    /**
     * @return array<string, mixed>
     */
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
