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
     * @ORM\Column(name="user_agent", type="string", length=255, unique=true)
     */
    protected $userAgent;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bot_filter", type="boolean")
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
     * @param \Mtt\BlogBundle\Entity\Tracking $trackings
     * @return TrackingAgent
     */
    public function addTracking(\Mtt\BlogBundle\Entity\Tracking $trackings)
    {
        $this->trackings[] = $trackings;

        return $this;
    }

    /**
     * Remove trackings
     *
     * @param \Mtt\BlogBundle\Entity\Tracking $trackings
     */
    public function removeTracking(\Mtt\BlogBundle\Entity\Tracking $trackings)
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
}