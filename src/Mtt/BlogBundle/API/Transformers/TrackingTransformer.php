<?php

namespace Mtt\BlogBundle\API\Transformers;

use League\Fractal\Resource\Collection;
use Mtt\BlogBundle\Entity\Tracking;
use Mtt\BlogBundle\Utils\EmojiFlagSymbol;

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
        $countryCode = null;
        $locationCity = null;
        $locationRegion = null;
        $locationCountry = null;
        $location = $item->getGeoLocation();
        if ($location && $city = $location->getCity()) {
            $locationCity = $city->getCity();
            $locationRegion = $city->getRegion();
            $locationCountry = $city->getCountry()->getName();
            $countryCode = $city->getCountry()->getCode();
        }

        try {
            $flag = $countryCode ? EmojiFlagSymbol::get($countryCode) : '';
        } catch (\Exception $e) {
            $flag = '';
        }

        return [
            'id' => $item->getId(),
            'statusCode' => $item->getStatusCode(),
            'ipAddr' => $item->getIpAddress(),
            'requestUri' => $item->getRequestURI(),
            'userAgent' => $item->getTrackingAgent() ? $item->getTrackingAgent()->getId() : null,
            'articleTitle' => $item->getPost() ? $item->getPost()->getTitle() : null,
            'articleSlug' => $item->getPost() ? $item->getPost()->getUrl() : null,
            'isCDN' => $item->isCdn(),
            'city' => $locationCity,
            'region' => $locationRegion,
            'country' => $locationCountry,
            'countryFlag' => $flag,
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
