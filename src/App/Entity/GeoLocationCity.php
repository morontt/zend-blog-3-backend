<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 14:27
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={
 *
 *   @ORM\UniqueConstraint(columns={"city", "region", "country_id"})
 * })
 *
 * @ORM\Entity(repositoryClass="App\Repository\GeoLocationCityRepository")
 */
class GeoLocationCity
{
    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $region;

    /**
     * @var float|null
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $latitude;

    /**
     * @var float|null
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $longitude;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $zip;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    protected $timeZone;

    /**
     * @var GeoLocationCountry
     *
     * @ORM\ManyToOne(targetEntity="GeoLocationCountry")
     *
     * @ORM\JoinColumn(nullable=false, onDelete="RESTRICT")
     */
    protected $country;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt")
     */
    protected $timeCreated;

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
    public function setCity($city)
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
    public function setRegion($region)
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
     * @param float $latitude
     *
     * @return GeoLocationCity
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return GeoLocationCity
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
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
    public function setZip($zip)
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
    public function setTimeZone($timeZone)
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
    public function setCountry(GeoLocationCountry $country)
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
    public function setTimeCreated($timeCreated)
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
