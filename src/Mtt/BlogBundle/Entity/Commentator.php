<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="commentators", uniqueConstraints={
 *   @ORM\UniqueConstraint(columns={"name", "mail", "website"})
 * })
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\CommentatorRepository")
 */
class Commentator
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
     * @var integer
     *
     * @ORM\Column(name="disqus_id", type="bigint", nullable=true)
     */
    protected $disqusId;

    /**
     * @var string
     *
     * @ORM\Column(name="email_hash", type="string", length=32, nullable=true)
     */
    protected $emailHash;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set mail
     *
     * @param string $mail
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
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set website
     *
     * @param string $website
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
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Add comments
     *
     * @param Comment $comments
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
     * @param integer $disqusId
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
     * @return integer 
     */
    public function getDisqusId()
    {
        return $this->disqusId;
    }

    /**
     * Set emailHash
     *
     * @param string $emailHash
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
     * @return string 
     */
    public function getEmailHash()
    {
        return $this->emailHash;
    }
}
