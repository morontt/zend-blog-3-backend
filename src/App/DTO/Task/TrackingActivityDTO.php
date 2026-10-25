<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 19.10.2026
 * Time: 22:22
 */

namespace App\DTO\Task;

use DateTime;
use InvalidArgumentException;

class TrackingActivityDTO
{
    public string $addr;
    public string $userAgent;
    public string $requestedURI;
    public DateTime $time;
    public bool $isCDN;
    public int $statusCode;
    public ?int $locationID;
    public int $duration;
    public ?string $method;
    public bool $isBot;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): TrackingActivityDTO
    {
        $dto = new self();

        $dto->addr = $data['ip_addr'];
        $dto->userAgent = $data['user_agent'];
        $dto->requestedURI = $data['url'];
        $dto->isCDN = (bool)$data['is_cdn'];
        $dto->statusCode = (int)$data['status'];
        $dto->locationID = $data['location_id'] ?? null;
        $dto->duration = (int)($data['duration'] ?? 0);
        $dto->method = $data['method'] ?? null;
        $dto->isBot = (bool)($data['is_bot'] ?? false);

        $time = DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $data['time'] ?? '');
        if ($time === false) {
            throw new InvalidArgumentException('Incorrect time');
        }
        $dto->time = $time;

        return $dto;
    }
}
