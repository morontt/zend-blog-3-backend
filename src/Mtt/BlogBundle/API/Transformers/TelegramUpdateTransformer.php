<?php

namespace Mtt\BlogBundle\API\Transformers;

use League\Fractal\Resource\Collection;
use Mtt\BlogBundle\Entity\TelegramUpdate;

class TelegramUpdateTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'telegramUser',
    ];

    public function transform(TelegramUpdate $item): array
    {
        return [
            'id' => $item->getId(),
            'user' => $item->getTelegramUser() ? $item->getTelegramUser()->getId() : null,
            'message' => $item->getTextMessage(),
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
        ];
    }

    /**
     * @param TelegramUpdate $entity
     *
     * @return Collection
     */
    public function includeTelegramUser(TelegramUpdate $entity): Collection
    {
        $items = [$entity->getTelegramUser()];

        return $this->collection($items, new TelegramUserTransformer(), 'telegramUsers');
    }
}
