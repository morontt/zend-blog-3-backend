<?php

namespace Mtt\BlogBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mtt\BlogBundle\Entity\Embedded\NestedSet;
use Mtt\BlogBundle\Entity\Traits\ModifyEntityTrait;
use Mtt\UserBundle\Entity\User;

/**
 * @ORM\Table(name="comments", indexes={
 *   @ORM\Index(name="left_key_idx", columns={"tree_left_key"}),
 *   @ORM\Index(name="right_key_idx", columns={"tree_right_key"}),
 *   @ORM\Index(columns={"ip_long"})
 * })
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment implements CommentInterface
{
    use ModifyEntityTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     **/
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumn(onDelete="SET NULL")
     **/
    protected $parent;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $post;

    /**
     * @var Commentator
     *
     * @ORM\ManyToOne(targetEntity="Commentator", inversedBy="comments")
     */
    protected $commentator;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Mtt\UserBundle\Entity\User", inversedBy="comments")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    protected $deleted = false;

    /**
     * @var TrackingAgent
     *
     * @ORM\ManyToOne(targetEntity="TrackingAgent")
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
     * @var GeoLocation
     *
     * @ORM\ManyToOne(targetEntity="GeoLocation")
     * @ORM\JoinColumn(name="ip_long", referencedColumnName="ip_long", onDelete="SET NULL")
     */
    private $geoLocation;

    /**
     * @var NestedSet
     *
     * @ORM\Embedded(class="Mtt\BlogBundle\Entity\Embedded\NestedSet", columnPrefix = "tree_")
     */
    private $nestedSet;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->timeCreated = new DateTime();
        $this->nestedSet = new NestedSet();
    }

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
     * Add children
     *
     * @param Comment $children
     *
     * @return Comment
     */
    public function addChild(self $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Comment $children
     */
    public function removeChild(self $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param Comment|null $parent
     *
     * @return Comment
     */
    public function setParent(self $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Comment|null
     */
    public function getParent(): ?Comment
    {
        return $this->parent;
    }

    /**
     * Set commentator
     *
     * @param Commentator|null $commentator
     *
     * @return Comment
     */
    public function setCommentator(Commentator $commentator = null)
    {
        $this->commentator = $commentator;

        return $this;
    }

    /**
     * Get commentator
     *
     * @return Commentator|null
     */
    public function getCommentator(): ?Commentator
    {
        return $this->commentator;
    }

    /**
     * Set user
     *
     * @param User|null $user
     *
     * @return Comment
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set text
     *
     * @param string $text
     *
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
     * @param bool $deleted
     *
     * @return Comment
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Is deleted
     *
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
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
     * Set post
     *
     * @param Post $post
     *
     * @return Comment
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
     * Set trackingAgent
     *
     * @param TrackingAgent $trackingAgent
     *
     * @return Comment
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
     * Get geoLocation
     *
     * @return GeoLocation
     */
    public function getGeoLocation()
    {
        return $this->geoLocation;
    }

    /**
     * @param GeoLocation|null $location
     *
     * @return $this
     */
    public function setGeoLocation(GeoLocation $location = null): self
    {
        $this->geoLocation = $location;

        return $this;
    }

    /**
     * @return NestedSet
     */
    public function getNestedSet(): NestedSet
    {
        return $this->nestedSet;
    }

    /**
     * @param NestedSet $nestedSet
     *
     * @return $this
     */
    public function setNestedSet(NestedSet $nestedSet): self
    {
        $this->nestedSet = $nestedSet;

        return $this;
    }
}
