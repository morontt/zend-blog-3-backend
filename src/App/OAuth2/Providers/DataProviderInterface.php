<?php

namespace App\OAuth2\Providers;

use App\Entity\UserExtraInfo;

interface DataProviderInterface
{
    public const YANDEX = 'yandex';
    public const VK = 'vkontakte';

    public function AvatarURL(UserExtraInfo $extraInfo): ?string;
}
