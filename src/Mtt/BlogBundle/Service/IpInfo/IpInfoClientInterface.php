<?php

namespace Mtt\BlogBundle\Service\IpInfo;

interface IpInfoClientInterface
{
    public function getLocationInfo(string $ip): ?LocationInfo;
}
