<?php

/**
 * User: morontt
 * Date: 06.05.2025
 * Time: 15:52
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 *
 * @ORM\Entity()
 */
class LjCommentMeta
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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, unique=true)
     */
    private $ljName;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     */
    private $posterId;

    /**
     * @var Commentator|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Commentator")
     */
    private $commentator;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLjName(): string
    {
        return $this->ljName;
    }

    public function setLjName(string $ljName): self
    {
        $this->ljName = $ljName;

        return $this;
    }

    public function getPosterId(): int
    {
        return $this->posterId;
    }

    public function setPosterId(int $posterId): self
    {
        $this->posterId = $posterId;

        return $this;
    }

    public function getCommentator(): ?Commentator
    {
        return $this->commentator;
    }

    public function setCommentator(?Commentator $commentator): self
    {
        $this->commentator = $commentator;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
