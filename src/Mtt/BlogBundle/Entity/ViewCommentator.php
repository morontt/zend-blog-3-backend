<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="v_commentators")
 * @ORM\Entity(readOnly=true)
 */
class ViewCommentator implements CommentatorInterface
{
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
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $mail;

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
     * @return null|int
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
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get mail
     *
     * @return null|string
     */
    public function getMail(): ? string
    {
        return $this->mail;
    }

    /**
     * Get website
     *
     * @return null|string
     */
    public function getWebsite() : ? string
    {
        return $this->website;
    }

    /**
     * Get disqusId
     *
     * @return null|int
     */
    public function getDisqusId() : ? int
    {
        return $this->disqusId;
    }

    /**
     * Get emailHash
     *
     * @return null|string
     */
    public function getEmailHash() : ? string
    {
        return $this->emailHash;
    }
}
