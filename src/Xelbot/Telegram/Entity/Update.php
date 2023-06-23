<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 08.10.17
 * Time: 19:00
 */

namespace Xelbot\Telegram\Entity;

class Update
{
    /**
     * @var int
     */
    protected $updateId;

    /**
     * @var Message|null
     */
    protected $message;

    /**
     * @var Message
     */
    protected $editedMessage;

    /**
     * @var Message
     */
    protected $channelPost;

    /**
     * @var Message
     */
    protected $editedChannelPost;

    /**
     * @return int
     */
    public function getUpdateId(): int
    {
        return $this->updateId;
    }

    /**
     * @param int $updateId
     *
     * @return Update
     */
    public function setUpdateId(int $updateId): self
    {
        $this->updateId = $updateId;

        return $this;
    }

    /**
     * @return Message|null
     */
    public function getMessage(): ?Message
    {
        return $this->message;
    }

    /**
     * @param Message|null $message
     *
     * @return Update
     */
    public function setMessage(Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Message|null
     */
    public function getEditedMessage(): ?Message
    {
        return $this->editedMessage;
    }

    /**
     * @param Message|null $editedMessage
     *
     * @return Update
     */
    public function setEditedMessage(Message $editedMessage = null): self
    {
        $this->editedMessage = $editedMessage;

        return $this;
    }

    /**
     * @return Message|null
     */
    public function getChannelPost(): ?Message
    {
        return $this->channelPost;
    }

    /**
     * @param Message|null $channelPost
     *
     * @return Update
     */
    public function setChannelPost(Message $channelPost = null): self
    {
        $this->channelPost = $channelPost;

        return $this;
    }

    /**
     * @return Message|null
     */
    public function getEditedChannelPost(): ?Message
    {
        return $this->editedChannelPost;
    }

    /**
     * @param Message|null $editedChannelPost
     *
     * @return Update
     */
    public function setEditedChannelPost(Message $editedChannelPost = null): self
    {
        $this->editedChannelPost = $editedChannelPost;

        return $this;
    }
}
