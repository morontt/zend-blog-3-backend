<?php

declare(strict_types=1);

namespace App\Service\IpInfo;

use App\Exception\AppException;
use App\LogTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

#[When(env: 'prod')]
#[When(env: 'dev')]
class IpInfoDbClient implements IpInfoClientInterface
{
    use LogTrait;

    public function __construct(
        private HttpClientInterface $client,
        LoggerInterface $logger,
        private string $key,
    ) {
        $this->setLogger($logger);
    }

    public function getLocationInfo(string $ip): ?LocationInfo
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->error(
                'Invalid IP address',
                ['ip' => $ip]
            );

            return null;
        }

        try {
            $this->info(
                'Request location by IP address',
                ['ip' => $ip]
            );

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
            }

            $this->error(
                'HTTP status error',
                ['code' => $response->getStatusCode(), 'message' => $response->getContent()]
            );

            throw new AppException('HTTP status error');
        } catch (Throwable $e) {
            $this->critical(
                'An error occurred',
                ['exception' => $e]
            );

            throw $e;
        }
    }

    public function isLimitedRequests(): bool
    {
        return true;
    }
}
