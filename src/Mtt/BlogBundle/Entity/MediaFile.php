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
use Mtt\BlogBundle\Entity\Traits\ModifyEntityTrait;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\MediaFileRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MediaFile
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
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned": true})
     */
    private $width;

    /**
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned": true})
     */
    private $height;

    public function __construct()
    {
        $this->timeCreated = new DateTime();
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

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int|null $width
     *
     * @return MediaFile
     */
    public function setWidth(?int $width): MediaFile
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     *
     * @return MediaFile
     */
    public function setHeight(?int $height): MediaFile
    {
        $this->height = $height;

        return $this;
    }
}
