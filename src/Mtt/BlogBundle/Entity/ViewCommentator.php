<?php

namespace Mtt\BlogBundle\Entity;

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
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=80, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    protected $website;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint", nullable=true, unique=true)
     */
    protected $disqusId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $emailHash;

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId(): ? int
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
    public function getEmail(): ? string
    {
        return $this->email;
    }

    /**
     * Get website
     *
     * @return string|null
     */
    public function getWebsite(): ? string
    {
        return $this->website;
    }

    /**
     * Get disqusId
     *
     * @return int|null
     */
    public function getDisqusId(): ? int
    {
        return $this->disqusId;
    }

    /**
     * Get emailHash
     *
     * @return string|null
     */
    public function getEmailHash(): ? string
    {
        return $this->emailHash;
    }
}
