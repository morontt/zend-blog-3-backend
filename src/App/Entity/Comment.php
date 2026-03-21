<?php

namespace App\Entity;

use App\Entity\Embedded\NestedSet;
use App\Entity\Traits\ModifyEntityTrait;
use App\Repository\CommentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'comments')]
#[ORM\Index(name: 'left_key_idx', columns: ['tree_left_key'])]
#[ORM\Index(name: 'right_key_idx', columns: ['tree_right_key'])]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Comment implements CommentInterface
{
    use ModifyEntityTrait;

    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected $id;

    /**
     * @var Collection<int, Comment>
     **/
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'parent')]
    protected $children;

    /**
     * @var Comment|null
     *
     *
     **/
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: Comment::class, inversedBy: 'children')]
    protected $parent;

    /**
     * @var Post
     */
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'comments')]
    protected $post;

    /**
     * @var Commentator|null
     */
    #[ORM\ManyToOne(targetEntity: Commentator::class, inversedBy: 'comments')]
    protected $commentator;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    protected $user;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    protected $text;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    protected $deleted = false;

    /**
     * @var TrackingAgent|null
     */
    #[ORM\JoinColumn(name: 'user_agent_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\ManyToOne(targetEntity: TrackingAgent::class)]
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
     * @var NestedSet
     */
    #[ORM\Embedded(class: NestedSet::class, columnPrefix: 'tree_')]
    private $nestedSet;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(type: 'milliseconds_dt', nullable: true)]
    private $forceCreatedAt;

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
     * @return string
     */
    public function getAvatarHash(): string
    {
        $hash = '';
        if ($this->getCommentator()) {
            $hash = $this->getCommentator()->getAvatarHash();
        } elseif ($this->getUser()) {
            $hash = $this->getUser()->getAvatarHash();
        }

        return $hash;
    }

    /**
     * Add children
     *
     * @param Comment $children
     *
     * @return Comment
     */
    public function addChild(self $children): self
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Comment $children
     */
    public function removeChild(self $children): void
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return Collection<int, Comment>
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
    public function setParent(?self $parent = null): self
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
    public function setCommentator(?Commentator $commentator = null): self
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
    public function setUser(?User $user = null): self
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
    public function setText($text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText(): string
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
    public function setDeleted($deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Is deleted
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Set ipAddress
     *
     * @param string|null $ipAddress
     *
     * @return Comment
     */
    public function setIpAddress($ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string|null
     */
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getPost(): Post
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
    public function setTrackingAgent(?TrackingAgent $trackingAgent = null): self
    {
        $this->trackingAgent = $trackingAgent;

        return $this;
    }

    /**
     * Get trackingAgent
     *
     * @return TrackingAgent
     */
    public function getTrackingAgent(): ?TrackingAgent
    {
        return $this->trackingAgent;
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

    public function getForceCreatedAt(): ?DateTime
    {
        return $this->forceCreatedAt;
    }

    public function setForceCreatedAt(?DateTime $forceCreatedAt = null): self
    {
        $this->forceCreatedAt = $forceCreatedAt;

        return $this;
    }
}
