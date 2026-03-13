<?php

declare(strict_types=1);

namespace App\API\Transformers;

use App\Entity\TelegramUpdate;
use League\Fractal\Resource\ResourceInterface;

class TelegramUpdateTransformer extends BaseTransformer
{
    /**
     * @var string[]
     */
    protected array $availableIncludes = [
        'telegramUser',
    ];

    /**
     * @return array<string, mixed>
     */
    public function transform(TelegramUpdate $item): array
    {
        return [
            'id' => $item->getId(),
            'user' => $item->getTelegramUser() ? $item->getTelegramUser()->getId() : null,
            'message' => $item->getTextMessage(),
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
            'replyId' => 0,
        ];
    }

    /**
     * @param TelegramUpdate $entity
     *
     * @return ResourceInterface
     */
    public function includeTelegramUser(TelegramUpdate $entity): ResourceInterface
    {
        $tgUser = $entity->getTelegramUser();
        $items = [];
        if ($tgUser) {
            $items = [$tgUser];
        }

        return $this->collection($items, new TelegramUserTransformer(), 'telegramUsers');
    }
}
