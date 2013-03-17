<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="commentators", uniqueConstraints={
 * @ORM\UniqueConstraint(columns={"name", "mail", "website"})
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
     * @param \Mtt\BlogBundle\Entity\Comment $comments
     * @return Commentator
     */
    public function addComment(\Mtt\BlogBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Mtt\BlogBundle\Entity\Comment $comments
     */
    public function removeComment(\Mtt\BlogBundle\Entity\Comment $comments)
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
}
