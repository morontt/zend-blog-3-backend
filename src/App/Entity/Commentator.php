<?php

namespace App\Entity;

use App\Entity\Traits\Gravatar;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="commentators", uniqueConstraints={
 *
 *   @ORM\UniqueConstraint(columns={"name", "mail", "website"})
 * })
 *
 * @ORM\Entity(repositoryClass="App\Repository\CommentatorRepository")
 */
class Commentator implements CommentatorInterface
{
    use Gravatar;

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
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mail", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fakeEmail;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $emailCheck;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    private $website;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="commentator")
     */
    private $comments;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default": 1, "comment":"1: male, 2: female"})
     */
    private $gender = User::MALE;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $rottenLink = false;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $rottenCheck;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isValidEmail(): bool
    {
        return $this->getEmail() && !is_null($this->isFakeEmail()) && !$this->isFakeEmail();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Commentator
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Commentator
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Commentator
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * Add comments
     *
     * @param Comment $comments
     *
     * @return Commentator
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
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     *
     * @return Commentator
     */
    public function setGender(int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isFakeEmail(): ?bool
    {
        return $this->fakeEmail;
    }

    /**
     * @param bool|null $fakeEmail
     *
     * @return Commentator
     */
    public function setFakeEmail(?bool $fakeEmail): self
    {
        $this->fakeEmail = $fakeEmail;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEmailCheck(): ?DateTime
    {
        return $this->emailCheck;
    }

    /**
     * @param DateTime|null $emailCheck
     *
     * @return Commentator
     */
    public function setEmailCheck(?DateTime $emailCheck): self
    {
        $this->emailCheck = $emailCheck;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRottenLink(): bool
    {
        return $this->rottenLink;
    }

    /**
     * @param bool $rottenLink
     *
     * @return Commentator
     */
    public function setRottenLink(bool $rottenLink): self
    {
        $this->rottenLink = $rottenLink;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getRottenCheck(): ?DateTime
    {
        return $this->rottenCheck;
    }

    /**
     * @param DateTime|null $rottenCheck
     *
     * @return Commentator
     */
    public function setRottenCheck(?DateTime $rottenCheck): self
    {
        $this->rottenCheck = $rottenCheck;

        return $this;
    }
}
