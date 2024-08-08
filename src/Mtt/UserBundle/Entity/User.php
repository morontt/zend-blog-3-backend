<?php

namespace Mtt\UserBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Utils\HashId;
use Serializable;
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
 *   fields={"email"},
 *   message="This email is already used"
 * )
 */
class User implements UserInterface, Serializable
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
     * @ORM\Column(name="username", type="string", length=128, unique=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=64, unique=true)
     * @Assert\Email()
     */
    protected $email;

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
     * @ORM\Column(type="string", length=24)
     */
    protected $wsseKey;

    /**
     * @var string
     *
     * @deprecated
     *
     * @ORM\Column(name="user_type", type="string", length=16)
     */
    protected $userType;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="time_created", type="milliseconds_dt")
     */
    protected $timeCreated;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_login", type="milliseconds_dt", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var int
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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Mtt\BlogBundle\Entity\Comment", mappedBy="user")
     */
    protected $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();

        $this->setRandomSalt();
        $this->setRandomWsseKey();

        $this->timeCreated = new DateTime();
        $this->userType = 'admin'; //TODO remove fake field
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->salt
            ) = unserialize($serialized);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @return string
     */
    public function getAvatarHash(): string
    {
        return HashId::hash($this->getId(), HashId::TYPE_USER | HashId::MALE);
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    public function setRandomSalt(): void
    {
        try {
            $randomBytes = random_bytes(16);
        } catch (\Exception $e) {
            $randomBytes = openssl_random_pseudo_bytes(16, $isSourceStrong);
            if ($isSourceStrong === false || $randomBytes === false) {
                throw new \RuntimeException('IV generation failed');
            }
        }

        $this->salt = bin2hex($randomBytes);
    }

    public function setRandomWsseKey(): void
    {
        try {
            $randomBytes = random_bytes(18);
        } catch (\Exception $e) {
            $randomBytes = openssl_random_pseudo_bytes(18, $isSourceStrong);
            if ($isSourceStrong === false || $randomBytes === false) {
                throw new \RuntimeException('IV generation failed');
            }
        }

        $this->wsseKey = strtr(base64_encode($randomBytes), '+/', '-_');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
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
     *
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
     * @return string
     */
    public function getWsseKey(): string
    {
        return $this->wsseKey;
    }

    /**
     * @param string $wsseKey
     *
     * @return $this
     */
    public function setWsseKey(string $wsseKey): self
    {
        $this->wsseKey = $wsseKey;

        return $this;
    }

    /**
     * Set userType
     *
     * @param string $userType
     *
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
     * @param DateTime $timeCreated
     *
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
     * @return DateTime
     */
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }

    /**
     * Set lastLogin
     *
     * @param DateTime $lastLogin
     *
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
     * @return DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set ipAddressLast
     *
     * @param string $ipAddressLast
     *
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
     *
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
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set loginCount
     *
     * @param int $loginCount
     *
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
     * @return int
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }
}
