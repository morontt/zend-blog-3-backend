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
    private $id;

    /**
     * @var Post|null
     */
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: Post::class)]
    private $post;

    /**
     * @var TrackingAgent|null
     */
    #[ORM\JoinColumn(name: 'user_agent_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: TrackingAgent::class, inversedBy: 'trackings')]
    private $trackingAgent;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'ip_addr', type: 'string', length: 15, nullable: true)]
    private $ipAddress;

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
    private $timeCreated;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(type: 'milliseconds_dt', nullable: true)]
    private $eventTime;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'is_cdn', type: 'boolean', options: ['default' => false])]
    private $cdn = false;

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

    /**
     * @var bool
     */
    #[ORM\Column(name: 'is_js', type: 'boolean', options: ['default' => false])]
    private $js = false;

    public function __construct()
    {
        $this->setTimeCreated(new DateTime());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setTimeCreated(DateTime $timeCreated): self
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }

    public function setTrackingAgent(?TrackingAgent $trackingAgent = null): self
    {
        $this->trackingAgent = $trackingAgent;

        return $this;
    }

    public function getTrackingAgent(): ?TrackingAgent
    {
        return $this->trackingAgent;
    }

    public function setPost(?Post $post = null): self
    {
        $this->post = $post;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function isCdn(): bool
    {
        return $this->cdn;
    }

    public function setCdn(bool $cdn): self
    {
        $this->cdn = $cdn;

        return $this;
    }

    public function getRequestURI(): ?string
    {
        return $this->requestURI;
    }

    public function setRequestURI(?string $requestURI = null): self
    {
        $this->requestURI = $requestURI;

        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(?int $statusCode = null): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getGeoLocation(): ?GeoLocation
    {
        return $this->geoLocation;
    }

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

    public function isJs(): bool
    {
        return $this->js;
    }

    public function setJs(bool $js): self
    {
        $this->js = $js;

        return $this;
    }

    public function setEventTime(DateTime $time): self
    {
        $this->eventTime = $time;

        return $this;
    }

    public function getEventTime(): DateTime
    {
        return $this->eventTime ?: $this->timeCreated;
    }
}
