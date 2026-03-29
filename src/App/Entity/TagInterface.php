<?php

/**
 * User: morontt
 * Date: 29.03.2026
 * Time: 17:47
 */

namespace App\Entity;

interface TagInterface
{
    public function getId(): ?int;

    public function getName(): string;

    public function getUrl(): string;

    public function getPostsCount(): int;
}
