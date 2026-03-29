<?php

namespace App\Entity;

use App\Entity\Traits\ModifyEntityTrait;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'posts')]
#[ORM\Index(columns: ['timestamp_sort'])]
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Post
{
    use ModifyEntityTrait;

    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 128)]
    private $title;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $url;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private $hide = false;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text', name: 'text_post')]
    private $text;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    private $preview;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    private $rawText;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    /**
     * @var Category
     */
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'posts')]
    private $category;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, Tag>
     */
    #[ORM\JoinTable(name: 'relation_topictag')]
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts')]
    private $tags;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private $commentsCount = 0;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private $viewsCount = 0;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post')]
    private $comments;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, MediaFile>|ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: MediaFile::class, mappedBy: 'post')]
    private $mediaFiles;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP', 'comment' => 'updated if article content changes'])]
    private $updatedAt;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private $disableComments = false;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(type: 'milliseconds_dt', nullable: true)]
    private $forceCreatedAt;

    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer', nullable: true, options: ['unsigned' => true])]
    private $timestampSort;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->mediaFiles = new ArrayCollection();

        $this->timeCreated = new DateTime();
        $this->updatedAt = new DateTime();
    }

    #[ORM\PrePersist]
    public function recalculateSortField(): void
    {
        $dt = $this->forceCreatedAt ?? $this->timeCreated;
        $this->timestampSort = $dt->getTimestamp();
    }

    public function hashedId(): int
    {
        return ($this->id << 7) + (crc32($this->url) & 0b1111111);
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $url
     *
     * @return Post
     */
    public function setUrl($url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param bool $hide
     *
     * @return Post
     */
    public function setHide($hide): self
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHide()
    {
        return $this->hide;
    }

    /**
     * @param string $text
     *
     * @return Post
     */
    public function setText($text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $description
     *
     * @return Post
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param Category $category
     *
     * @return Post
     */
    public function setCategory(?Category $category = null): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Tag $tag
     *
     * @return Post
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, Tag>
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Comment $comments
     *
     * @return Post
     */
    public function addComment(Comment $comments): self
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * @param Comment $comments
     */
    public function removeComment(Comment $comments): void
    {
        $this->comments->removeElement($comments);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, Comment>
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param MediaFile $mediaFile
     *
     * @return Post
     */
    public function addMediaFile(MediaFile $mediaFile): self
    {
        $this->mediaFiles[] = $mediaFile;

        return $this;
    }

    /**
     * @param MediaFile $mediaFile
     */
    public function removeMediaFile(MediaFile $mediaFile): void
    {
        $this->mediaFiles->removeElement($mediaFile);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, MediaFile>
     */
    public function getMediaFiles()
    {
        return $this->mediaFiles;
    }

    public function getDefaultImage(): ?MediaFile
    {
        $criteria = Criteria::create(true)
            ->andWhere(Criteria::expr()->eq('defaultImage', true))
        ;

        return $this->mediaFiles->matching($criteria)->first() ?: null;
    }

    /**
     * @param string $rawText
     *
     * @return Post
     */
    public function setRawText($rawText): self
    {
        $this->rawText = $rawText;

        return $this;
    }

    /**
     * @return string
     */
    public function getRawText()
    {
        return $this->rawText;
    }

    /**
     * @param int $commentsCount
     *
     * @return Post
     */
    public function setCommentsCount($commentsCount): self
    {
        $this->commentsCount = $commentsCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    /**
     * @param int $viewsCount
     *
     * @return Post
     */
    public function setViewsCount($viewsCount): self
    {
        $this->viewsCount = $viewsCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
    }

    /**
     * @return string
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @param string $preview
     *
     * @return Post
     */
    public function setPreview($preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     *
     * @return Post
     */
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDisableComments(): bool
    {
        return $this->disableComments;
    }

    /**
     * @param bool $disableComments
     *
     * @return Post
     */
    public function setDisableComments(bool $disableComments): self
    {
        $this->disableComments = $disableComments;

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

    public function getVirtualCreated(): DateTime
    {
        return $this->forceCreatedAt ?? $this->timeCreated;
    }
}
