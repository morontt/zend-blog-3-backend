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
     * @return int|null
     */
    public function getDisqusId(): ?int;

    /**
     * @return string|null
     */
    public function getEmailHash(): ?string;

    /**
     * @return string
     */
    public function getAvatarHash(): string;
}
