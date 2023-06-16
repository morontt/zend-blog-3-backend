<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 11:58
 */

namespace Mtt\BlogBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\GeoLocationRepository")
 */
class GeoLocation
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Mtt\BlogBundle\Doctrine\ORM\IpLongIdGenerator")
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $ipLong;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_addr", type="string", length=15, unique=true)
     */
    private $ipAddress;

    /**
     * @var GeoLocationCity
     *
     * @ORM\ManyToOne(targetEntity="GeoLocationCity")
     * @ORM\JoinColumn(nullable=true, onDelete="RESTRICT")
     */
    private $city;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt")
     */
    private $timeCreated;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned": true, "default": 0})
     */
    private $countOfCheck = 0;

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
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return GeoLocation
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set city
     *
     * @param GeoLocationCity $city
     *
     * @return GeoLocation
     */
    public function setCity(GeoLocationCity $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return GeoLocationCity
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set timeCreated
     *
     * @param DateTime $timeCreated
     *
     * @return GeoLocation
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
