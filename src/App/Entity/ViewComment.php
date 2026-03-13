<?php

namespace App\Entity;

use App\Entity\Traits\Gravatar;
use App\Repository\ViewCommentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'v_comments')]
#[ORM\Entity(readOnly: true, repositoryClass: ViewCommentRepository::class)]
class ViewComment implements CommentInterface
{
    use Gravatar;

    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    protected $id;

    /**
     * @var Collection<int, ViewComment>
     **/
    #[ORM\OneToMany(targetEntity: ViewComment::class, mappedBy: 'parent')]
    protected $children;

    /**
     * @var self|null
     *
     *
     **/
    #[ORM\JoinColumn]
    #[ORM\ManyToOne(targetEntity: ViewComment::class, inversedBy: 'children')]
    protected $parent;

    /**
     * @var Post
     */
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Post::class)]
    protected $post;

    /**
     * @var int
     */
    #[ORM\Column(name: 'uid', type: 'integer')]
    protected $virtualUserId;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 80)]
    protected $username;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'mail', type: 'string', length: 80, nullable: true)]
    protected $email;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 160, nullable: true)]
    protected $website;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    protected $text;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'ip_addr', type: 'string', length: 15, nullable: true)]
    protected $ipAddress;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 128)]
    protected $city;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 128)]
    protected $region;

    /**
     * Country name based on ISO 3166.
     *
     * @var string
     */
    #[ORM\Column(name: 'country_name', type: 'string', length: 64)]
    protected $country;

    /**
     * Two-character country code based on ISO 3166.
     *
     * @var string
     */
    #[ORM\Column(name: 'country_code', type: 'string', length: 2, unique: true)]
    protected $code;

    /**
     * @var float
     */
    #[ORM\Column(type: 'float')]
    protected $latitude;

    /**
     * @var float
     */
    #[ORM\Column(type: 'float')]
    protected $longitude;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 8)]
    protected $timeZone;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    protected $deleted = false;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'milliseconds_dt')]
    protected $timeCreated;

    /**
     * @var int
     */
    #[ORM\Column(type: 'smallint')]
    private $gender = User::MALE;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text', length: 65000)]
    protected $userAgent;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', name: 'is_bot')]
    protected $bot = false;

    /**
     * @var int
     */
    #[ORM\Column(type: 'smallint')]
    private $avatarVariant = 0;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->timeCreated = new DateTime();
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
     * Get virtualUserId
     *
     * @return int
     */
    public function getVirtualUserId()
    {
        return $this->virtualUserId;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get website
     *
     * @return string|null
     */
    public function getWebsite()
    {
        return $this->website;
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
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Get code
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
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
     * Get timeCreated
     *
     * @return DateTime
     */
    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }

    /**
     * Get children
     *
     * @return Collection<int, ViewComment>
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get parent
     *
     * @return ViewComment
     */
    public function getParent(): ?ViewComment
    {
        return $this->parent;
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
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get timeZone
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
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
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->bot;
    }

    public function getAvatarVariant(): int
    {
        return $this->avatarVariant;
    }
}
