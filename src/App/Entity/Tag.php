<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'tags')]
#[ORM\Entity(repositoryClass: TagRepository::class)]
#[UniqueEntity(fields: ['name'])]
#[UniqueEntity(fields: ['url'])]
class Tag implements TagInterface
{
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
    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private $name;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $url;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, Post>
     */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'tags')]
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function addPost(Post $posts): self
    {
        $this->posts[] = $posts;

        return $this;
    }

    public function removePost(Post $posts): void
    {
        $this->posts->removeElement($posts);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, Post>
     */
    public function getPosts()
    {
        return $this->posts;
    }

    public function getPostsCount(): int
    {
        return 0;
    }
}
