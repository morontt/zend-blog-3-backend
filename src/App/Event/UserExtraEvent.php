<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\UserExtraInfo;
use Symfony\Contracts\EventDispatcher\Event;

class UserExtraEvent extends Event
{
    public function __construct(
        private UserExtraInfo $extraInfo,
        private bool $newUser,
    ) {
    }

    public function getExtraInfo(): UserExtraInfo
    {
        return $this->extraInfo;
    }

    public function isNewUser(): bool
    {
        return $this->newUser;
    }
}
