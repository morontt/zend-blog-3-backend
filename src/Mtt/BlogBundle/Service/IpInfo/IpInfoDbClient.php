<?php

namespace Mtt\BlogBundle\Service\IpInfo;

class IpInfoDbClient implements IpInfoClientInterface
{
    private string $key;

    /**
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getLocationInfo(string $ip): ?LocationInfo
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $params = http_build_query([
                'key' => $this->key,
                'ip' => $ip,
                'format' => 'json',
            ]);

            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                ],
            ]);
            try {
                $json = file_get_contents('https://api.ipinfodb.com/v3/ip-city/?' . $params, false, $context);

                return LocationInfo::createFromArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
            } catch (\Throwable $e) {
                return null;
            }
        }

        return null;
    }
}
