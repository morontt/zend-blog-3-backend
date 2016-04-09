<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 03.04.16
 * Time: 23:21
 */

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\MediaFileRepository")
 */
class MediaFile
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
     * @var integer
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $timeCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $lastUpdate;


    public function __construct()
    {
        $now = new \DateTime();

        $this->timeCreated = $now;
        $this->lastUpdate = $now;
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
     * Set path
     *
     * @param string $path
     *
     * @return MediaFile
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set timeCreated
     *
     * @param \DateTime $timeCreated
     *
     * @return MediaFile
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
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     *
     * @return MediaFile
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
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
     * Set description
     *
     * @param string $description
     *
     * @return MediaFile
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     *
     * @return MediaFile
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return integer
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set defaultImage
     *
     * @param boolean $defaultImage
     *
     * @return MediaFile
     */
    public function setDefaultImage($defaultImage)
    {
        $this->defaultImage = $defaultImage;

        return $this;
    }

    /**
     * Is defaultImage
     *
     * @return boolean
     */
    public function isDefaultImage()
    {
        return $this->defaultImage;
    }

    /**
     * Set backuped
     *
     * @param boolean $backuped
     *
     * @return MediaFile
     */
    public function setBackuped($backuped)
    {
        $this->backuped = $backuped;

        return $this;
    }

    /**
     * Is backuped
     *
     * @return boolean
     */
    public function isBackuped()
    {
        return $this->backuped;
    }
}
