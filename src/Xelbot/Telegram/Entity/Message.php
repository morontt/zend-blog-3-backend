<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 08.10.17
 * Time: 18:58
 */

namespace Xelbot\Telegram\Entity;

class Message
{
    /**
     * @var int
     */
    protected $messageId;

    /**
     * @var User
     */
    protected $from;

    /**
     * @var Chat
     */
    protected $chat;

    /**
     * @var int
     */
    protected $date;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $entities;

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     *
     * @return Message
     */
    public function setMessageId(int $messageId): Message
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * @return User
     */
    public function getFrom(): User
    {
        return $this->from;
    }

    /**
     * @param User $from
     *
     * @return Message
     */
    public function setFrom(User $from): Message
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @param Chat $chat
     *
     * @return Message
     */
    public function setChat(Chat $chat): Message
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param int $date
     *
     * @return Message
     */
    public function setDate(int $date): Message
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return Message
     */
    public function setText(string $text): Message
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param array $entities
     *
     * @return Message
     */
    public function setEntities(array $entities): Message
    {
        $this->entities = $entities;

        return $this;
    }
}
