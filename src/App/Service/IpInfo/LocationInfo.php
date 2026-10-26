<?php

declare(strict_types=1);

namespace App\Service\IpInfo;

use LogicException;

class LocationInfo
{
    /**
     * @var string[]
     */
    private static array $stringProperties = [
        'countryCode',
        'countryName',
        'regionName',
        'cityName',
    ];

    /**
     * @var string[]
     */
    private static array $nullStringProperties = [
        'zipCode',
        'latitude',
        'longitude',
        'timeZone',
    ];

    public string $countryCode;

    public string $countryName;

    public string $cityName;

    public string $regionName;

    public ?string $latitude = null;

    public ?string $longitude = null;

    public ?string $timeZone = null;

    public ?string $zipCode = null;

    /**
     * @param array<string, string> $data
     */
    public static function createFromArray(array $data): LocationInfo
    {
        if (!array_key_exists('countryCode', $data)
            || !array_key_exists('countryName', $data)
            || !array_key_exists('regionName', $data)
            || !array_key_exists('cityName', $data)
        ) {
            throw new LogicException('Missing required properties');
        }

        $self = new self();
        foreach (self::$stringProperties as $property) {
            $self->$property = (string)($data[$property] ?? '-');
        }
        foreach (self::$nullStringProperties as $property) {
            $self->$property = !empty($data[$property]) ? (string)($data[$property]) : null;
        }

        return $self;
    }
}
