<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 11:58
 */

namespace Mtt\BlogBundle\Entity;

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
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_addr", type="string", length=15, unique=true)
     */
    protected $ipAddress;

    /**
     * @var GeoLocationCity
     *
     * @ORM\ManyToOne(targetEntity="GeoLocationCity")
     * @ORM\JoinColumn(nullable=false, onDelete="RESTRICT")
     */
    protected $city;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $timeCreated;

    public function __construct()
    {
        $this->timeCreated = new \DateTime();
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
     * @param \DateTime $timeCreated
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
     * @return \DateTime
     */
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }
}
