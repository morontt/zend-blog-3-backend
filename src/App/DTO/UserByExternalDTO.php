<?php
/**
 * User: morontt
 * Date: 22.04.2025
 * Time: 09:03
 */

namespace App\DTO;

use App\Entity\User;

class UserByExternalDTO
{
    private ?User $user;
    private bool $found;

    public function __construct(?User $user, bool $found)
    {
        $this->user = $user;
        $this->found = $found;
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
