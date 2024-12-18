<?php

namespace App\Entity;

use App\Entity\Traits\ModifyEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="telegram_updates")
 *
 * @ORM\Entity(repositoryClass="App\Repository\TelegramUpdateRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class TelegramUpdate
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
     * @var TelegramUser|null
     *
     * @ORM\ManyToOne(targetEntity="TelegramUser")
     *
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $telegramUser;

    /**
     * @var int|null
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $chatId;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $textMessage;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535)
     */
    private $rawMessage;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return TelegramUser|null
     */
    public function getTelegramUser(): ?TelegramUser
    {
        return $this->telegramUser;
    }

    /**
     * @param TelegramUser|null $telegramUser
     *
     * @return TelegramUpdate
     */
    public function setTelegramUser(?TelegramUser $telegramUser): self
    {
        $this->telegramUser = $telegramUser;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getChatId(): ?int
    {
        return $this->chatId;
    }

    /**
     * @param int|null $chatId
     *
     * @return TelegramUpdate
     */
    public function setChatId(?int $chatId): self
    {
        $this->chatId = $chatId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRawMessage(): string
    {
        return $this->rawMessage;
    }

    /**
     * @param string $rawMessage
     *
     * @return TelegramUpdate
     */
    public function setRawMessage(string $rawMessage): self
    {
        $this->rawMessage = $rawMessage;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTextMessage(): ?string
    {
        return $this->textMessage;
    }

    /**
     * @param string|null $textMessage
     *
     * @return TelegramUpdate
     */
    public function setTextMessage(?string $textMessage = null): self
    {
        $this->textMessage = $textMessage;

        return $this;
    }
}
