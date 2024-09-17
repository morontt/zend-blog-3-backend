<?php

namespace App\Entity;

use DateTime;

interface CommentatorInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @return string|null
     */
    public function getWebsite(): ?string;

    /**
     * @return string
     */
    public function getAvatarHash(): string;

    /**
     * @return bool|null
     */
    public function isFakeEmail(): ?bool;

    /**
     * @return DateTime|null
     */
    public function getEmailCheck(): ?DateTime;

    /**
     * @return int
     */
    public function getGender(): int;
}
