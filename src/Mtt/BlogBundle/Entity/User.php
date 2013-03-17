<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\UserRepository")
 */
class User
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
     * @ORM\Column(name="username", type="string", length=128, unique=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=64, unique=true)
     */
    protected $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="password_salt", type="string", length=32)
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", length=16)
     */
    protected $userType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_created", type="datetime")
     */
    protected $timeCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_last", type="datetime", nullable=true)
     */
    protected $timeLast;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_addr", type="string", length=15, nullable=true)
     */
    protected $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_last", type="string", length=15, nullable=true)
     */
    protected $ipAddressLast;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     */
    protected $posts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    protected $comments;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return User
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
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set userType
     *
     * @param string $userType
     * @return User
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set timeCreated
     *
     * @param \DateTime $timeCreated
     * @return User
     */
    public function setTimeCreated($timeCreated)
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    /**
     * Get timeCreated
     *
     * @return \DateTime
     */
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }

    /**
     * Set timeLast
     *
     * @param \DateTime $timeLast
     * @return User
     */
    public function setTimeLast($timeLast)
    {
        $this->timeLast = $timeLast;

        return $this;
    }

    /**
     * Get timeLast
     *
     * @return \DateTime
     */
    public function getTimeLast()
    {
        return $this->timeLast;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return User
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set ipAddressLast
     *
     * @param string $ipAddressLast
     * @return User
     */
    public function setIpAddressLast($ipAddressLast)
    {
        $this->ipAddressLast = $ipAddressLast;

        return $this;
    }

    /**
     * Get ipAddressLast
     *
     * @return string
     */
    public function getIpAddressLast()
    {
        return $this->ipAddressLast;
    }

    /**
     * Add posts
     *
     * @param \Mtt\BlogBundle\Entity\Post $posts
     * @return User
     */
    public function addPost(\Mtt\BlogBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Mtt\BlogBundle\Entity\Post $posts
     */
    public function removePost(\Mtt\BlogBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add comments
     *
     * @param \Mtt\BlogBundle\Entity\Comment $comments
     * @return User
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
