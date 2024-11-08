<?php

namespace App\Entity;

use App\Entity\Embedded\NestedSet;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="category", indexes={
 *
 *   @ORM\Index(name="left_key_idx", columns={"tree_left_key"}),
 *   @ORM\Index(name="right_key_idx", columns={"tree_right_key"})
 * })
 *
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 *
 * @UniqueEntity(fields={"url"})
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     **/
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     *
     * @ORM\JoinColumn(onDelete="SET NULL")
     **/
    protected $parent;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $url;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
     */
    protected $posts;

    /**
     * @var NestedSet
     *
     * @ORM\Embedded(class="App\Entity\Embedded\NestedSet", columnPrefix = "tree_")
     */
    private $nestedSet;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->nestedSet = new NestedSet();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Category
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
     * Add children
     *
     * @param Category $children
     *
     * @return Category
     */
    public function addChild(self $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Category $children
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
     * @param Category|null $parent
     *
     * @return Category
     */
    public function setParent(?self $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add posts
     *
     * @param Post $posts
     *
     * @return Category
     */
    public function addPost(Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param Post $posts
     */
    public function removePost(Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return Collection
     */
    public function getPosts()
    {
        return $this->posts;
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
