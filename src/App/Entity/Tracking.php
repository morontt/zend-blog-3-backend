<?php

namespace App\Entity;

use App\Repository\TrackingRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'tracking')]
#[ORM\Entity(repositoryClass: TrackingRepository::class)]
class Tracking
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected $id;

    /**
     * @var Post|null
     */
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Post::class)]
    protected $post;

    /**
     * @var TrackingAgent|null
     */
    #[ORM\JoinColumn(name: 'user_agent_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: TrackingAgent::class, inversedBy: 'trackings')]
    protected $trackingAgent;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'ip_addr', type: 'string', length: 15, nullable: true)]
    protected $ipAddress;

    /**
     * @var GeoLocation|null
     */
    #[ORM\JoinColumn(name: 'ip_long', referencedColumnName: 'ip_long', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: GeoLocation::class)]
    private $geoLocation;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'milliseconds_dt')]
    protected $timeCreated;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'is_cdn', type: 'boolean', options: ['default' => false])]
    private $cdn;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private $requestURI;

    /**
     * @var int|null
     */
    #[ORM\Column(type: 'smallint', nullable: true)]
    private $statusCode;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', options: ['unsigned' => true, 'default' => 0])]
    private $duration = 0;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 8, nullable: true)]
    private $method;

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
    public function setIpAddress($ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     */
    public function getIpAddress(): ?string
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
    public function setTimeCreated(DateTime $timeCreated): self
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

    /**
     * Set trackingAgent
     *
     * @param TrackingAgent $trackingAgent
     *
     * @return Tracking
     */
    public function setTrackingAgent(?TrackingAgent $trackingAgent = null): self
    {
        $this->trackingAgent = $trackingAgent;

        return $this;
    }

    /**
     * Get trackingAgent
     */
    public function getTrackingAgent(): ?TrackingAgent
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
    public function setPost(?Post $post = null): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     */
    public function getPost(): ?Post
    {
        return $this->post;
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
    public function setCdn(bool $cdn): self
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
    public function setRequestURI(?string $requestURI = null): self
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
    public function setStatusCode(?int $statusCode = null): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get geoLocation
     *
     * @return GeoLocation|null
     */
    public function getGeoLocation(): ?GeoLocation
    {
        return $this->geoLocation;
    }

    /**
     * @param GeoLocation|null $location
     *
     * @return $this
     */
    public function setGeoLocation(?GeoLocation $location = null): self
    {
        $this->geoLocation = $location;

        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method = null): self
    {
        $this->method = $method;

        return $this;
    }
}
