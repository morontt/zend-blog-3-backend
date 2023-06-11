<?php

namespace Mtt\BlogBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @deprecated
     * @ORM\Column(type="boolean", name="is_bot", options={"default": false})
     */
    protected $bot = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt", options={"default": "CURRENT_TIMESTAMP(3)"})
     */
    protected $createdAt;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tracking", mappedBy="trackingAgent", cascade={"persist"})
     */
    protected $trackings;

    public function __construct()
    {
        $this->trackings = new ArrayCollection();
        $this->createdAt = new DateTime();
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
     * @return Collection
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
     * @param DateTime $createdAt
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
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->bot;
    }

    /**
     * @param bool $bot
     *
     * @return $this
     */
    public function setBot(bool $bot): self
    {
        $this->bot = $bot;

        return $this;
    }
}
