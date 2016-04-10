<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\PostRepository")
 */
class Post
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
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $url;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $hide = false;

    /**
     * @var string
     *
     * @ORM\Column(type="text", name="text_post")
     */
    protected $text;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $rawText;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $timeCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastUpdate;

    /**
     * @var \Mtt\BlogBundle\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
     */
    protected $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     * @ORM\JoinTable(name="relation_topictag")
     */
    protected $tags;

    /**
     * @var \Mtt\BlogBundle\Entity\PostCount
     *
     * @ORM\OneToOne(targetEntity="PostCount", mappedBy="post", cascade={"persist"})
     */
    protected $postCount;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    protected $comments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="MediaFile", mappedBy="post")
     */
    protected $mediaFiles;

    /**
     * @var integer
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $disqusThread;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->mediaFiles = new ArrayCollection();

        $now = new \DateTime();

        $this->timeCreated = $now;
        $this->lastUpdate = $now;

        $this->postCount = new PostCount();
        $this->postCount->setPost($this);
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
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Post
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set hide
     *
     * @param boolean $hide
     * @return Post
     */
    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * Get hide
     *
     * @return boolean
     */
    public function isHide()
    {
        return $this->hide;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Post
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
     * Set description
     *
     * @param string $description
     * @return Post
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
     * Set timeCreated
     *
     * @param \DateTime $timeCreated
     * @return Post
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
     * @return Post
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
     * Set category
     *
     * @param Category $category
     * @return Post
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Mtt\BlogBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add tag
     *
     * @param Tag $tag
     *
     * @return Post
     */
    public function addTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove tag
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set postCount
     *
     * @param PostCount $postCount
     * @return Post
     */
    public function setPostCount(PostCount $postCount = null)
    {
        $this->postCount = $postCount;

        return $this;
    }

    /**
     * Get postCount
     *
     * @return PostCount
     */
    public function getPostCount()
    {
        return $this->postCount;
    }

    /**
     * Add comments
     *
     * @param Comment $comments
     * @return Post
     */
    public function addComment(Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param Comment $comments
     */
    public function removeComment(Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set disqusThread
     *
     * @param integer $disqusThread
     * @return Post
     */
    public function setDisqusThread($disqusThread)
    {
        $this->disqusThread = $disqusThread;

        return $this;
    }

    /**
     * Get disqusThread
     *
     * @return integer
     */
    public function getDisqusThread()
    {
        return $this->disqusThread;
    }

    /**
     * Add mediaFile
     *
     * @param MediaFile $mediaFile
     *
     * @return Post
     */
    public function addMediaFile(MediaFile $mediaFile)
    {
        $this->mediaFiles[] = $mediaFile;

        return $this;
    }

    /**
     * Remove mediaFile
     *
     * @param MediaFile $mediaFile
     */
    public function removeMediaFile(MediaFile $mediaFile)
    {
        $this->mediaFiles->removeElement($mediaFile);
    }

    /**
     * Get mediaFiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMediaFiles()
    {
        return $this->mediaFiles;
    }

    /**
     * Set rawText
     *
     * @param string $rawText
     *
     * @return Post
     */
    public function setRawText($rawText)
    {
        $this->rawText = $rawText;

        return $this;
    }

    /**
     * Get rawText
     *
     * @return string
     */
    public function getRawText()
    {
        return $this->rawText;
    }
}
