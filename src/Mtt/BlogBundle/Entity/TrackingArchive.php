<?php

namespace Mtt\BlogBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tracking_archive", indexes={
 *   @ORM\Index(columns={"user_agent_id"})
 * })
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\TrackingArchiveRepository")
 */
class TrackingArchive
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
     * @var int
     *
     * @ORM\Column(name="post_id", type="integer")
     */
    protected $post;

    /**
     * @var int
     *
     * @ORM\Column(name="user_agent_id", type="integer", nullable=true)
     */
    protected $trackingAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_addr", type="string", length=15, nullable=true)
     */
    protected $ipAddress;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     */
    private $ipLong;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt")
     */
    protected $timeCreated;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_cdn", type="boolean", options={"default": false})
     */
    protected $cdn;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $requestURI;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $statusCode;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $method;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get post
     *
     * @return int
     */
    public function getPost(): ?int
    {
        return $this->post;
    }

    /**
     * Get trackingAgent
     *
     * @return int
     */
    public function getTrackingAgent(): ?int
    {
        return $this->trackingAgent;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getIpLong(): ?int
    {
        return $this->ipLong;
    }

    /**
     * Get timeCreated
     *
     * @return DateTime
     */
    public function getTimeCreated(): ?DateTime
    {
        return $this->timeCreated;
    }

    /**
     * @return bool
     */
    public function isCdn(): bool
    {
        return $this->cdn;
    }

    /**
     * @return string|null
     */
    public function getRequestURI(): ?string
    {
        return $this->requestURI;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }
}
