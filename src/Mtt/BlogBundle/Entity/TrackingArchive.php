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
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt")
     */
    protected $timeCreated;

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
     * Set post
     *
     * @param int $post
     *
     * @return TrackingArchive
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return int
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set trackingAgent
     *
     * @param int $trackingAgent
     *
     * @return TrackingArchive
     */
    public function setTrackingAgent($trackingAgent)
    {
        $this->trackingAgent = $trackingAgent;

        return $this;
    }

    /**
     * Get trackingAgent
     *
     * @return int
     */
    public function getTrackingAgent()
    {
        return $this->trackingAgent;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return TrackingArchive
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
     * @return TrackingArchive
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
