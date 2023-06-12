<?php

namespace Mtt\BlogBundle\API\Transformers;

use League\Fractal\Resource\Collection;
use Mtt\BlogBundle\Entity\Tracking;

class TrackingTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'trackingAgents',
    ];

    public function transform(Tracking $item)
    {
        return [
            'id' => $item->getId(),
            'statusCode' => $item->getStatusCode(),
            'ipAddr' => $item->getIpAddress(),
            'requestUri' => $item->getRequestURI(),
            'userAgent' => $item->getTrackingAgent() ? $item->getTrackingAgent()->getId() : null,
            'articleTitle' => $item->getPost() ? $item->getPost()->getTitle() : null,
            'articleSlug' => $item->getPost() ? $item->getPost()->getUrl() : null,
            'isCDN' => $item->isCdn(),
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
        ];
    }

    /**
     * @param Tracking $entity
     *
     * @return Collection
     */
    public function includeTrackingAgents(Tracking $entity): Collection
    {
        $agent = $entity->getTrackingAgent();
        $items = [];
        if ($agent) {
            $items = [$agent];
        }

        return $this->collection($items, new UserAgentTransformer(), 'userAgents');
    }
}
