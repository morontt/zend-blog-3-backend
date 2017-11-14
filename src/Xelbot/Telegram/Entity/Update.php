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
     * @var Message
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
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     *
     * @return Update
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Message
     */
    public function getEditedMessage()
    {
        return $this->editedMessage;
    }

    /**
     * @param Message $editedMessage
     *
     * @return Update
     */
    public function setEditedMessage(Message $editedMessage): self
    {
        $this->editedMessage = $editedMessage;

        return $this;
    }

    /**
     * @return Message
     */
    public function getChannelPost(): Message
    {
        return $this->channelPost;
    }

    /**
     * @param Message $channelPost
     *
     * @return Update
     */
    public function setChannelPost(Message $channelPost): self
    {
        $this->channelPost = $channelPost;

        return $this;
    }

    /**
     * @return Message
     */
    public function getEditedChannelPost(): Message
    {
        return $this->editedChannelPost;
    }

    /**
     * @param Message $editedChannelPost
     *
     * @return Update
     */
    public function setEditedChannelPost(Message $editedChannelPost): self
    {
        $this->editedChannelPost = $editedChannelPost;

        return $this;
    }
}
