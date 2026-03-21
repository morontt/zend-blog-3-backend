<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 22.04.2025
 * Time: 09:03
 */

namespace App\DTO;

use App\Entity\User;

class UserByExternalDTO
{
    public function __construct(
        private ?User $user,
        private bool $found,
    ) {
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function found(): bool
    {
        return $this->found;
    }
}
