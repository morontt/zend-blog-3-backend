<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 03.04.16
 * Time: 23:21
 */

namespace Mtt\BlogBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\MediaFileRepository")
 */
class MediaFile
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
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="mediaFiles")
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     */
    protected $post;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $path;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $fileSize;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $defaultImage = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $backuped = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $timeCreated;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $lastUpdate;

    public function __construct()
    {
        $now = new DateTime();

        $this->timeCreated = $now;
        $this->lastUpdate = $now;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return MediaFile
     */
    public function setPath(string $path): MediaFile
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getOriginalFileName(): string
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    /**
     * Set timeCreated
     *
     * @param DateTime $timeCreated
     *
     * @return MediaFile
     */
    public function setTimeCreated(DateTime $timeCreated): MediaFile
    {
        $this->timeCreated = $timeCreated;

        return $this;
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
     * Set lastUpdate
     *
     * @param DateTime $lastUpdate
     *
     * @return MediaFile
     */
    public function setLastUpdate(DateTime $lastUpdate): MediaFile
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return DateTime
     */
    public function getLastUpdate(): DateTime
    {
        return $this->lastUpdate;
    }

    /**
     * Set post
     *
     * @param Post $post
     *
     * @return MediaFile
     */
    public function setPost(Post $post = null): MediaFile
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * Set description
     *
     * @param string|null $description
     *
     * @return MediaFile
     */
    public function setDescription(string $description = null): MediaFile
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set fileSize
     *
     * @param int $fileSize
     *
     * @return MediaFile
     */
    public function setFileSize(int $fileSize): MediaFile
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * Set defaultImage
     *
     * @param bool $defaultImage
     *
     * @return MediaFile
     */
    public function setDefaultImage(bool $defaultImage): MediaFile
    {
        $this->defaultImage = $defaultImage;

        return $this;
    }

    /**
     * Is defaultImage
     *
     * @return bool
     */
    public function isDefaultImage(): bool
    {
        return $this->defaultImage;
    }

    /**
     * Set backuped
     *
     * @param bool $backuped
     *
     * @return MediaFile
     */
    public function setBackuped($backuped): MediaFile
    {
        $this->backuped = $backuped;

        return $this;
    }

    /**
     * Is backuped
     *
     * @return bool
     */
    public function isBackuped(): bool
    {
        return $this->backuped;
    }
}
