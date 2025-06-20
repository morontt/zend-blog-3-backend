<?php

namespace App\Entity;

use App\Utils\HashId;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use RuntimeException;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users")
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @DoctrineAssert\UniqueEntity(
 *   fields={"username"},
 *   message="This username is already used"
 * )
 * @DoctrineAssert\UniqueEntity(
 *   fields={"email"},
 *   message="This email is already used"
 * )
 */
class User implements UserInterface, LegacyPasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const TYPE_GUEST = 'guest';
    public const TYPE_ADMIN = 'admin';

    public const MALE = 1;
    public const FEMALE = 2;

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
     * @ORM\Column(name="username", type="string", length=128, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=64, unique=true)
     *
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password_hash", type="string", length=96)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="password_salt", type="string", length=32)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=24)
     */
    private $wsseKey;

    /**
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", length=16)
     */
    private $userType;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="time_created", type="milliseconds_dt")
     */
    private $timeCreated;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_login", type="milliseconds_dt", nullable=true)
     */
    private $lastLogin;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $loginCount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_last", type="string", length=15, nullable=true)
     */
    private $ipAddressLast;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $displayName;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default": 1, "comment":"1: male, 2: female"})
     */
    private $gender = self::MALE;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default": 0, "unsigned": true})
     */
    private $avatarVariant = 0;

    public function __construct()
    {
        $this->comments = new ArrayCollection();

        $this->setRandomSalt();
        $this->setRandomWsseKey();

        $this->timeCreated = new DateTime();
        $this->userType = self::TYPE_GUEST;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function __serialize(): array
    {
        return [
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
        ];
    }

    public function __unserialize(array $data): void
    {
        [
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
        ] = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = [self::ROLE_USER];
        if ($this->userType === self::TYPE_ADMIN) {
            $roles = [self::ROLE_ADMIN];
        }

        return $roles;
    }

    /**
     * @return string
     */
    public function getAvatarHash(): string
    {
        $genderOption = ($this->getGender() === self::MALE) ? HashId::MALE : HashId::FEMALE;

        $options = HashId::TYPE_USER | $genderOption;
        $options += $this->getAvatarVariant() << 4;

        return HashId::hash($this->getId(), $options);
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
        } catch (Exception $e) {
            $randomBytes = openssl_random_pseudo_bytes(16, $isSourceStrong);
            if ($isSourceStrong === false || $randomBytes === false) {
                throw new RuntimeException('IV generation failed');
            }
        }

        $this->salt = bin2hex($randomBytes);
    }

    public function setRandomWsseKey(): void
    {
        try {
            $randomBytes = random_bytes(18);
        } catch (Exception $e) {
            $randomBytes = openssl_random_pseudo_bytes(18, $isSourceStrong);
            if ($isSourceStrong === false || $randomBytes === false) {
                throw new RuntimeException('IV generation failed');
            }
        }

        $this->wsseKey = strtr(base64_encode($randomBytes), '+/', '-_');
    }

    public static function fakeEmail(string $prefix): string
    {
        return $prefix . '@xelbot.fake';
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): self
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
    public function setEmail(string $email): self
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
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt(): ?string
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
    public function setUserType(string $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return string
     */
    public function getUserType(): string
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
    public function setTimeCreated(DateTime $timeCreated): self
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    /**
     * Get timeCreated
     *
     * @return DateTime
     */
    public function getTimeCreated(): DateTime
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
    public function setLastLogin(DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return DateTime
     */
    public function getLastLogin(): DateTime
    {
        return $this->lastLogin;
    }

    /**
     * Set ipAddressLast
     *
     * @param string|null $ip
     *
     * @return User
     */
    public function setIpAddressLast(?string $ip = null): self
    {
        $this->ipAddressLast = $ip;

        return $this;
    }

    /**
     * Get ipAddressLast
     *
     * @return string|null
     */
    public function getIpAddressLast(): ?string
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
    public function addComment(Comment $comments): self
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param Comment $comments
     */
    public function removeComment(Comment $comments): void
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
    public function setLoginCount(int $loginCount): self
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get loginCount
     *
     * @return int
     */
    public function getLoginCount(): int
    {
        return $this->loginCount;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName = null): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function setGender(int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAvatarVariant(): int
    {
        return $this->avatarVariant;
    }

    public function setAvatarVariant(int $avatarVariant): self
    {
        $this->avatarVariant = $avatarVariant;

        return $this;
    }
}
