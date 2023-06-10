<?php

namespace Mtt\BlogBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tracking")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\TrackingRepository")
 */
class Tracking
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
     * @var \Mtt\BlogBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $post;

    /**
     * @var \Mtt\BlogBundle\Entity\TrackingAgent
     *
     * @ORM\ManyToOne(targetEntity="TrackingAgent", inversedBy="trackings")
     * @ORM\JoinColumn(name="user_agent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $trackingAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_addr", type="string", length=15, nullable=true)
     */
    protected $ipAddress;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt")
     */
    protected $timeCreated;

    /**
     * @var int
     *
     * @deprecated
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $timestampCreated;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_cdn", type="boolean", options={"default": false})
     */
    private $cdn;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $requestURI;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $statusCode;

    public function __construct()
    {
        $this->cdn = false;

        $this->setTimeCreated(new DateTime());
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
     * @return Tracking
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
     * Set timeCreated
     *
     * @param DateTime $timeCreated
     *
     * @return Tracking
     */
    public function setTimeCreated(DateTime $timeCreated)
    {
        $this->timeCreated = $timeCreated;
        $this->timestampCreated = (int)$timeCreated->format('U');

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

    /**
     * Set trackingAgent
     *
     * @param TrackingAgent $trackingAgent
     *
     * @return Tracking
     */
    public function setTrackingAgent(TrackingAgent $trackingAgent = null)
    {
        $this->trackingAgent = $trackingAgent;

        return $this;
    }

    /**
     * Get trackingAgent
     *
     * @return TrackingAgent
     */
    public function getTrackingAgent()
    {
        return $this->trackingAgent;
    }

    /**
     * Set post
     *
     * @param Post $post
     *
     * @return Tracking
     */
    public function setPost(Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set timestampCreated
     *
     * @param string $timestampCreated
     *
     * @return Tracking
     */
    public function setTimestampCreated($timestampCreated)
    {
        $this->timestampCreated = $timestampCreated;

        return $this;
    }

    /**
     * Get timestampCreated
     *
     * @return string
     */
    public function getTimestampCreated()
    {
        return $this->timestampCreated;
    }

    /**
     * @return bool
     */
    public function isCdn(): bool
    {
        return $this->cdn;
    }

    /**
     * @param bool $cdn
     *
     * @return Tracking
     */
    public function setCdn(bool $cdn): Tracking
    {
        $this->cdn = $cdn;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestURI(): ?string
    {
        return $this->requestURI;
    }

    /**
     * @param string|null $requestURI
     *
     * @return Tracking
     */
    public function setRequestURI(string $requestURI = null): self
    {
        $this->requestURI = $requestURI;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @param int|null $statusCode
     *
     * @return Tracking
     */
    public function setStatusCode(int $statusCode = null): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
