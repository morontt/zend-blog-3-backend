<?php

namespace Mtt\BlogBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Mtt\BlogBundle\Entity\Traits\Gravatar;

/**
 * @ORM\Table(name="v_commentators")
 * @ORM\Entity(readOnly=true)
 */
class ViewCommentator implements CommentatorInterface
{
    use Gravatar;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80)
     */
    private $name;

    /**
     * @var string
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
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $forceImage;

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
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * Get website
     *
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @return bool
     */
    public function isForceImage(): bool
    {
        return $this->forceImage;
    }

    /**
     * @return bool|null
     */
    public function isFakeEmail(): ?bool
    {
        return $this->fakeEmail;
    }

    /**
     * @return DateTime|null
     */
    public function getEmailCheck(): ?DateTime
    {
        return $this->emailCheck;
    }
}
