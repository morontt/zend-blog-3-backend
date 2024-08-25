<?php

namespace Mtt\UserBundle\OAuth\Providers;

use Mtt\UserBundle\Entity\UserExtraInfo;

interface DataProviderInterface
{
    public const YANDEX = 'yandex';

    public function AvatarURL(UserExtraInfo $extraInfo): ?string;
}
