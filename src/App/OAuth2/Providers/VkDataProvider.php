<?php

/**
 * User: morontt
 * Date: 07.09.2024
 * Time: 10:58
 */

namespace App\OAuth2\Providers;

use App\Entity\UserExtraInfo;

class VkDataProvider implements DataProviderInterface
{
    public function AvatarURL(UserExtraInfo $extraInfo): ?string
    {
        $url = null;
        if ($extraInfo->getAvatar()) {
            $urlData = parse_url($extraInfo->getAvatar());

            $queryParams = array_filter(explode('&', $urlData['query']), static function ($value) {
                return !(strpos($value, 'cs=') === 0);
            });

            $url = sprintf(
                '%s://%s%s?%s',
                $urlData['scheme'],
                $urlData['host'],
                $urlData['path'],
                implode('&', $queryParams)
            );
        }

        return $url;
    }
}
