<?php

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\TrackingAgent;

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
}
