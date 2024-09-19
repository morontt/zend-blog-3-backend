<?php

namespace App\OAuth2\Providers;

use App\Entity\UserExtraInfo;

class YandexDataProvider implements DataProviderInterface
{
    public function AvatarURL(UserExtraInfo $extraInfo): ?string
    {
        $url = null;
        if ($extraInfo->getAvatar()) {
            $url = sprintf(
                'https://avatars.yandex.net/get-yapic/%s/islands-200',
                $extraInfo->getAvatar()
            );
        }

        return $url;
    }
}
