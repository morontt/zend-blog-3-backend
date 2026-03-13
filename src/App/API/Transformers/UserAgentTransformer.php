<?php

declare(strict_types=1);

namespace App\API\Transformers;

use App\Entity\TrackingAgent;

class UserAgentTransformer extends BaseTransformer
{
    /**
     * @param TrackingAgent $item
     *
     * @return array<string, mixed>
     */
    public function transform(TrackingAgent $item): array
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getUserAgent(),
            'bot' => $item->isBot(),
            'createdAt' => $this->dateTimeToISO($item->getCreatedAt()),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function reverseTransform(TrackingAgent $entity, array $data): void
    {
        $entity
            ->setBot($data['bot'])
        ;
    }
}
