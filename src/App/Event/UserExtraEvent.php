<?php

namespace App\Event;

use App\Entity\UserExtraInfo;
use Symfony\Contracts\EventDispatcher\Event;

class UserExtraEvent extends Event
{
    private UserExtraInfo $extraInfo;

    /**
     * @param UserExtraInfo $extraInfo
     */
    public function __construct(UserExtraInfo $extraInfo)
    {
        $this->extraInfo = $extraInfo;
    }

    public function getExtraInfo(): UserExtraInfo
    {
        return $this->extraInfo;
    }
}
