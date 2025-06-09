<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.07.15
 * Time: 0:00
 */

namespace App\Utils;

class Http
{
    /**
     * @return string|null
     */
    public static function getClientIp(): ?string
    {
        if (self::getServer('HTTP_CLIENT_IP') !== null) {
            $ip = self::getServer('HTTP_CLIENT_IP');
        } elseif (self::getServer('HTTP_X_FORWARDED_FOR') !== null) {
            $ip = self::getServer('HTTP_X_FORWARDED_FOR');
        } else {
            $ip = self::getServer('REMOTE_ADDR');
        }

        return $ip;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public static function getServer(string $key): ?string
    {
        return $_SERVER[$key] ?? null;
    }
}
