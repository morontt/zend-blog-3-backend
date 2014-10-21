<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\CommentRepository")
 */
class Comment
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     **/
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    protected $parent;

    /**
     * @var \Mtt\BlogBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    /**
     * @var \Mtt\BlogBundle\Entity\Commentator
     *
     * @ORM\ManyToOne(targetEntity="Commentator", inversedBy="comments")
     * @ORM\JoinColumn(name="commentator_id", referencedColumnName="id", nullable=true)
     */
    protected $commentator;

    /**
     * @var \Mtt\BlogBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $deleted = false;

    /**
     * @var \Mtt\BlogBundle\Entity\TrackingAgent
     *
     * @ORM\ManyToOne(targetEntity="TrackingAgent")
     * @ORM\JoinColumn(name="user_agent_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
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
     * @ORM\Column(name="disqus_id", type="integer", nullable=true)
     */
    protected $disqusId;


    public function __construct()
    {
        $this->children = new ArrayCollection();
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
     * Add children
     *
     * @param \Mtt\BlogBundle\Entity\Comment $children
     * @return Comment
     */
    public function addChild(\Mtt\BlogBundle\Entity\Comment $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Mtt\BlogBundle\Entity\Comment $children
     */
    public function removeChild(\Mtt\BlogBundle\Entity\Comment $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Mtt\BlogBundle\Entity\Comment $parent
     * @return Comment
     */
    public function setParent(\Mtt\BlogBundle\Entity\Comment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Mtt\BlogBundle\Entity\Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set commentator
     *
     * @param \Mtt\BlogBundle\Entity\Commentator $commentator
     * @return Comment
     */
    public function setCommentator(\Mtt\BlogBundle\Entity\Commentator $commentator = null)
    {
        $this->commentator = $commentator;

        return $this;
    }

    /**
     * Get commentator
     *
     * @return \Mtt\BlogBundle\Entity\Commentator
     */
    public function getCommentator()
    {
        return $this->commentator;
    }

    /**
     * Set user
     *
     * @param \Mtt\BlogBundle\Entity\User $user
     * @return Comment
     */
    public function setUser(\Mtt\BlogBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Mtt\BlogBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Comment
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return Comment
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
     * @return Comment
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
     * Set post
     *
     * @param \Mtt\BlogBundle\Entity\Post $post
     * @return Comment
     */
    public function setPost(\Mtt\BlogBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Mtt\BlogBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set trackingAgent
     *
     * @param \Mtt\BlogBundle\Entity\TrackingAgent $trackingAgent
     * @return Comment
     */
    public function setTrackingAgent(\Mtt\BlogBundle\Entity\TrackingAgent $trackingAgent = null)
    {
        $this->trackingAgent = $trackingAgent;

        return $this;
    }

    /**
     * Get trackingAgent
     *
     * @return \Mtt\BlogBundle\Entity\TrackingAgent
     */
    public function getTrackingAgent()
    {
        return $this->trackingAgent;
    }

    /**
     * Set disqusId
     *
     * @param integer $disqusId
     * @return Comment
     */
    public function setDisqusId($disqusId)
    {
        $this->disqusId = $disqusId;

        return $this;
    }

    /**
     * Get disqusId
     *
     * @return integer 
     */
    public function getDisqusId()
    {
        return $this->disqusId;
    }
}
