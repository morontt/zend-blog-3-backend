<?php

namespace App\API\Transformers;

use App\Entity\TrackingAgent;

class UserAgentTransformer extends BaseTransformer
{
    /**
     * @param TrackingAgent $item
     *
     * @return array
     */
    public function transform(TrackingAgent $item)
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getUserAgent(),
            'bot' => $item->isBot(),
            'createdAt' => $this->dateTimeToISO($item->getCreatedAt()),
        ];
    }

    /**
     * @param TrackingAgent $entity
     * @param array $data
     *
     * @return void
     */
    public static function reverseTransform(TrackingAgent $entity, array $data)
    {
        $entity
            ->setBot($data['bot'])
        ;
    }
}
