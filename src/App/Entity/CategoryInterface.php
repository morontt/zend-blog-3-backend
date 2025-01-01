<?php
/**
 * User: morontt
 * Date: 01.01.2025
 * Time: 20:13
 */

namespace App\Entity;

use App\Entity\Embedded\NestedSet;

interface CategoryInterface
{
    public function getId(): ?int;

    public function getName(): string;

    public function getUrl(): string;

    public function getNestedSet(): NestedSet;

    public function getParent();

    public function getPostsCount(): int;
}
