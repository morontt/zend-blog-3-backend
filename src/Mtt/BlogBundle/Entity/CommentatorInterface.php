<?php

namespace Mtt\BlogBundle\Entity;

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
     * @return bool
     */
    public function isForceImage(): bool;
}
