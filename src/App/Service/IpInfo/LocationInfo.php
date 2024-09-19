<?php

namespace App\Service\IpInfo;

class LocationInfo
{
    /**
     * @var string[]
     */
    private static array $properties = [
        'countryCode',
        'countryName',
        'regionName',
        'cityName',
        'zipCode',
        'latitude',
        'longitude',
        'timeZone',
    ];

    /**
     * @var string
     */
    public string $countryCode;

    /**
     * @var string
     */
    public string $countryName;

    /**
     * @var string
     */
    public string $cityName;

    /**
     * @var string
     */
    public string $regionName;

    /**
     * @var string|null
     */
    public $latitude;

    /**
     * @var string|null
     */
    public $longitude;

    /**
     * @var string|null
     */
    public $timeZone;

    /**
     * @var string|null
     */
    public $zipCode;

    /**
     * @param array $data
     *
     * @return LocationInfo
     */
    public static function createFromArray(array $data): LocationInfo
    {
        if (!array_key_exists('countryCode', $data)
            || !array_key_exists('countryName', $data)
            || !array_key_exists('regionName', $data)
            || !array_key_exists('cityName', $data)
        ) {
            throw new \LogicException('Missing required properties');
        }

        $self = new self();
        foreach (static::$properties as $property) {
            $self->$property = $data[$property] ?? null;
        }

        return $self;
    }
}
