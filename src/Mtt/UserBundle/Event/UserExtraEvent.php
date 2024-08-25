<?php

namespace Mtt\UserBundle\Event;

use Mtt\UserBundle\Entity\UserExtraInfo;
use Symfony\Component\EventDispatcher\Event;

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
