<?php

namespace App\Service\IpInfo;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class IpInfoDbClient implements IpInfoClientInterface
{
    private string $key;

    private HttpClientInterface $client;

    private LoggerInterface $logger;

    /**
     * @param HttpClientInterface $client
     * @param LoggerInterface $logger
     * @param string $key
     */
    public function __construct(HttpClientInterface $client, LoggerInterface $logger, string $key)
    {
        $this->key = $key;
        $this->client = $client;
        $this->logger = $logger;
    }

    public function getLocationInfo(string $ip): ?LocationInfo
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            try {
                $response = $this->client->request('GET', 'https://api.ipinfodb.com/v3/ip-city/', [
                    'query' => [
                        'key' => $this->key,
                        'ip' => $ip,
                        'format' => 'json',
                    ],
                    'timeout' => 5,
                ]);

                if ($response->getStatusCode() === 200) {
                    return LocationInfo::createFromArray(json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR));
                } else {
                    $this->logger->error(
                        'IpInfoDbClient error',
                        ['code' => $response->getStatusCode(), 'message' => $response->getContent()]
                    );
                }
            } catch (Throwable $e) {
                $this->logger->critical(
                    'IpInfoDbClient error',
                    ['exception' => $e]
                );

                return null;
            }
        }

        return null;
    }
}
