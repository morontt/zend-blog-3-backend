<?php

/**
 * User: morontt
 * Date: 01.01.2025
 * Time: 21:37
 */

namespace App\Entity;

use App\Entity\Embedded\NestedSet;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="v_category")
 *
 * @ORM\Entity(readOnly=true, repositoryClass="App\Repository\ViewCategoryRepository")
 */
class ViewCategory implements CategoryInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ViewCategory")
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var NestedSet
     *
     * @ORM\Embedded(class="App\Entity\Embedded\NestedSet", columnPrefix = "tree_")
     */
    private $nestedSet;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="cnt")
     */
    private $postsCount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getNestedSet(): NestedSet
    {
        return $this->nestedSet;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getPostsCount(): int
    {
        return $this->postsCount;
    }
}
