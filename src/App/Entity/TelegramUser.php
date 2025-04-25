<?php

namespace App\Entity;

use App\Entity\Traits\ModifyEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="telegram_users")
 *
 * @ORM\Entity(repositoryClass="App\Repository\TelegramUserRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class TelegramUser
{
    use ModifyEntityTrait;

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
     * @var int
     *
     * @ORM\Column(type="bigint")
     */
    private $userId;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_bot", type="boolean")
     */
    private $bot;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", length=65535)
     */
    private $rawMessage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lang", type="string", length=8, nullable=true)
     */
    private $language;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     *
     * @return TelegramUser
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->bot;
    }

    /**
     * @param bool $bot
     *
     * @return TelegramUser
     */
    public function setBot(bool $bot): self
    {
        $this->bot = $bot;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return TelegramUser
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     *
     * @return TelegramUser
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     *
     * @return TelegramUser
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRawMessage(): ?string
    {
        return $this->rawMessage;
    }

    /**
     * @param string|null $rawMessage
     *
     * @return TelegramUser
     */
    public function setRawMessage(?string $rawMessage): self
    {
        $this->rawMessage = $rawMessage;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string|null $language
     *
     * @return TelegramUser
     */
    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }
}
