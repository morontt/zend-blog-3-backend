<?php

namespace App\Entity;

use DateTime;

interface CommentInterface
{
    public function getId(): ?int;

    public function getText(): string;

    public function getIpAddress(): ?string;

    public function isDeleted(): bool;

    public function getTimeCreated(): DateTime;

    /**
     * @return CommentInterface|null
     */
    public function getParent();
}
