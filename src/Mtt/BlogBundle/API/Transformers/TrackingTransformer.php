<?php

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Tracking;

class TrackingTransformer extends BaseTransformer
{
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
}
