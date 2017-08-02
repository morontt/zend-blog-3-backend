<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="commentators", uniqueConstraints={
 *   @ORM\UniqueConstraint(columns={"name", "mail", "website"})
 * })
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\CommentatorRepository")
 */
class Commentator implements CommentatorInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="commentator")
     */
    protected $comments;

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

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * @param string $name
     * @param string $mail
     * @param string $website
     *
     * @return string
     */
    public static function getAvatarHash($name, $mail, $website)
    {
        if ($mail) {
            $hash = md5(strtolower(trim($mail)));
        } else {
            $hash = md5(strtolower(trim($name)));
            if ($website) {
                $hash = md5($hash . strtolower(trim($website)));
            }
        }

        return $hash;
    }

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
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Commentator
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
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
     * @return null|string
     */
    public function getWebsite() : ? string
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set disqusId
     *
     * @param int $disqusId
     *
     * @return Commentator
     */
    public function setDisqusId($disqusId)
    {
        $this->disqusId = $disqusId;

        return $this;
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
     * Set emailHash
     *
     * @param string $emailHash
     *
     * @return Commentator
     */
    public function setEmailHash($emailHash)
    {
        $this->emailHash = $emailHash;

        return $this;
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
