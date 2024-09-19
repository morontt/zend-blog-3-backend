<?php

namespace App\Service\IpInfo;

interface IpInfoClientInterface
{
    public function getLocationInfo(string $ip): ?LocationInfo;
}
