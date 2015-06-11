<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tracking")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\TrackingRepository")
 */
class Tracking
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
     * @var \Mtt\BlogBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
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
     * @var \DateTime
     *
     * @ORM\Column(name="time_created", type="datetime")
     */
    protected $timeCreated;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $timestampCreated;

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
     * Set ipAddress
     *
     * @param string $ipAddress
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
     * @param \DateTime $timeCreated
     * @return Tracking
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

    /**
     * Set trackingAgent
     *
     * @param TrackingAgent $trackingAgent
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
}
