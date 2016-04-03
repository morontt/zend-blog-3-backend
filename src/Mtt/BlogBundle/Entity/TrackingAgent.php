<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="tracking_agent")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\TrackingAgentRepository")
 */
class TrackingAgent
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65000)
     */
    protected $userAgent;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, unique=true)
     */
    protected $hash;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $botFilter;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Tracking", mappedBy="trackingAgent", cascade={"persist"})
     */
    protected $trackings;


    public function __construct()
    {
        $this->trackings = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     * @return TrackingAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        $this->hash = md5($userAgent);

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set botFilter
     *
     * @param boolean $botFilter
     * @return TrackingAgent
     */
    public function setBotFilter($botFilter)
    {
        $this->botFilter = $botFilter;

        return $this;
    }

    /**
     * Get botFilter
     *
     * @return boolean
     */
    public function getBotFilter()
    {
        return $this->botFilter;
    }

    /**
     * Add trackings
     *
     * @param Tracking $trackings
     * @return TrackingAgent
     */
    public function addTracking(Tracking $trackings)
    {
        $this->trackings[] = $trackings;

        return $this;
    }

    /**
     * Remove trackings
     *
     * @param Tracking $trackings
     */
    public function removeTracking(Tracking $trackings)
    {
        $this->trackings->removeElement($trackings);
    }

    /**
     * Get trackings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrackings()
    {
        return $this->trackings;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return TrackingAgent
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
}
