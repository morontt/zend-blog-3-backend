<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_login_histories")
 *
 * @ORM\Entity()
 */
class LoginHistory
{
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ip_addr", type="string", length=15, nullable=true)
     */
    private $ipAddress;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt", options={"default": "CURRENT_TIMESTAMP(3)"})
     */
    private $timeCreated;

    public function __construct()
    {
        $this->timeCreated = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setIpAddress(?string $ipAddress = null): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }
}
