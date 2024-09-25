<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={
 *   @ORM\UniqueConstraint(columns={"external_id", "data_provider"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\UserExtraInfoRepository")
 */
class UserExtraInfo
{
    public const MALE = 1;
    public const FEMALE = 2;
    public const UNKNOWN = 3;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $externalId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=16)
     */
    private $dataProvider;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $displayName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $firstName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $lastName;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default": 3, "comment":"1: male, 2: female, 3: n/a"})
     */
    private $gender = self::UNKNOWN;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535)
     */
    private $rawData;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt", options={"default": "CURRENT_TIMESTAMP(3)"})
     */
    private $timeCreated;

    /**
     * @var TrackingAgent|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TrackingAgent")
     * @ORM\JoinColumn(name="user_agent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $trackingAgent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ip_addr", type="string", length=15, nullable=true)
     */
    protected $ipAddress;

    /**
     * @var GeoLocation|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\GeoLocation")
     * @ORM\JoinColumn(name="ip_long", referencedColumnName="ip_long", onDelete="SET NULL")
     */
    private $geoLocation;

    public function __construct()
    {
        $this->timeCreated = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getDataProvider(): string
    {
        return $this->dataProvider;
    }

    public function setDataProvider(string $dataProvider): self
    {
        $this->dataProvider = $dataProvider;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRawData(): string
    {
        return $this->rawData;
    }

    public function setRawData(string $rawData): self
    {
        $this->rawData = $rawData;

        return $this;
    }

    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }

    public function setTimeCreated(DateTime $timeCreated): self
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    public function getTrackingAgent(): ?TrackingAgent
    {
        return $this->trackingAgent;
    }

    public function setTrackingAgent(TrackingAgent $trackingAgent = null): self
    {
        $this->trackingAgent = $trackingAgent;

        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getGeoLocation(): ?GeoLocation
    {
        return $this->geoLocation;
    }

    public function setGeoLocation(GeoLocation $geoLocation = null): self
    {
        $this->geoLocation = $geoLocation;

        return $this;
    }
}
