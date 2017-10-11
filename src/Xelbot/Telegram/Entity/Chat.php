<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 08.10.17
 * Time: 21:56
 */

namespace Xelbot\Telegram\Entity;

class Chat
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $type;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Chat
     */
    public function setId(int $id): Chat
    {
        $this->id = $id;

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
     * @return Chat
     */
    public function setFirstName(string $firstName): Chat
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return Chat
     */
    public function setLastName(string $lastName): Chat
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return Chat
     */
    public function setUsername(string $username): Chat
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Chat
     */
    public function setType(string $type): Chat
    {
        $this->type = $type;

        return $this;
    }
}
