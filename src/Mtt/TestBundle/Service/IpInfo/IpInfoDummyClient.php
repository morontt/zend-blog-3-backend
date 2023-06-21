<?php

namespace Mtt\TestBundle\Service\IpInfo;

use Mtt\BlogBundle\Service\IpInfo\IpInfoClientInterface;
use Mtt\BlogBundle\Service\IpInfo\LocationInfo;

class IpInfoDummyClient implements IpInfoClientInterface
{
    /**
     * @param string $ip
     *
     * @return LocationInfo|null
     */
    public function getLocationInfo(string $ip): ?LocationInfo
    {
        return LocationInfo::createFromArray([
            'countryCode' => 'SU',
            'countryName' => 'Soviet Union',
            'regionName' => 'Lukomorie',
            'cityName' => 'Myxosransk',
        ]);
    }
}
