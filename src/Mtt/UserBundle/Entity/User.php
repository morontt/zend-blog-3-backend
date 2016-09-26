<?php

namespace Mtt\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Mtt\BlogBundle\Entity\Comment;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Mtt\UserBundle\Entity\Repository\UserRepository")
 * @DoctrineAssert\UniqueEntity(
 *   fields={"username"},
 *   message="This username is already used"
 * )
 * @DoctrineAssert\UniqueEntity(
 *   fields={"mail"},
 *   message="This email is already used"
 * )
 */
class User implements UserInterface, \Serializable
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
     * @Assert\Email()
     */
    protected $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=96)
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
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $loginCount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_last", type="string", length=15, nullable=true)
     */
    protected $ipAddressLast;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Mtt\BlogBundle\Entity\Comment", mappedBy="user")
     */
    protected $comments;

    /**
     * @var string
     *
     * @ORM\Column(name="email_hash", type="string", length=32, nullable=true)
     */
    protected $emailHash;


    public function __construct()
    {
        $this->comments = new ArrayCollection();

        $this->salt = md5(uniqid('', true));
        $this->timeCreated = new \DateTime();
        $this->userType = 'admin'; //TODO remove fake field
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt
            ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
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
        $this->emailHash = md5($mail);

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
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
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
     * Add comments
     *
     * @param Comment $comments
     * @return User
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
     * Set emailHash
     *
     * @param string $emailHash
     * @return User
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

    /**
     * Set loginCount
     *
     * @param integer $loginCount
     * @return User
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get loginCount
     *
     * @return integer
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }
}
