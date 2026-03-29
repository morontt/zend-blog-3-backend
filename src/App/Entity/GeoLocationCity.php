<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 14:27
 */

namespace App\Entity;

use App\Repository\GeoLocationCityRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table]
#[ORM\UniqueConstraint(columns: ['city', 'region', 'country_id'])]
#[ORM\Entity(repositoryClass: GeoLocationCityRepository::class)]
class GeoLocationCity
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 128)]
    private $city;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 128)]
    private $region;

    /**
     * @var float|null
     */
    #[ORM\Column(type: 'float', nullable: true)]
    private $latitude;

    /**
     * @var float|null
     */
    #[ORM\Column(type: 'float', nullable: true)]
    private $longitude;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $zip;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 8, nullable: true)]
    private $timeZone;

    /**
     * @var GeoLocationCountry
     */
    #[ORM\JoinColumn(nullable: false, onDelete: 'RESTRICT')]
    #[ORM\ManyToOne(targetEntity: GeoLocationCountry::class)]
    private $country;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'milliseconds_dt')]
    private $timeCreated;

    public function __construct()
    {
        $this->timeCreated = new DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return GeoLocationCity
     */
    public function setCity($city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return GeoLocationCity
     */
    public function setRegion($region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set latitude
     *
     * @param float|string|null $latitude
     *
     * @return GeoLocationCity
     */
    public function setLatitude($latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float|null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float|string|null $longitude
     *
     * @return GeoLocationCity
     */
    public function setLongitude($longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float|null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return GeoLocationCity
     */
    public function setZip($zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set timeZone
     *
     * @param string $timeZone
     *
     * @return GeoLocationCity
     */
    public function setTimeZone($timeZone): self
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * Get timeZone
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * Set country
     *
     * @param GeoLocationCountry $country
     *
     * @return GeoLocationCity
     */
    public function setCountry(GeoLocationCountry $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return GeoLocationCountry
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set timeCreated
     *
     * @param DateTime $timeCreated
     *
     * @return GeoLocationCity
     */
    public function setTimeCreated($timeCreated): self
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    /**
     * Get timeCreated
     *
     * @return DateTime
     */
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }
}
