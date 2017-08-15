<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tracking_agent")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\TrackingAgentRepository")
 */
class TrackingAgent
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
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $botFilter = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Tracking", mappedBy="trackingAgent", cascade={"persist"})
     */
    protected $trackings;

    public function __construct()
    {
        $this->trackings = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
     * Set userAgent
     *
     * @param string $userAgent
     *
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
     * @param bool $botFilter
     *
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
     * @return bool
     */
    public function getBotFilter()
    {
        return $this->botFilter;
    }

    /**
     * Add trackings
     *
     * @param Tracking $trackings
     *
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
     *
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TrackingAgent
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
