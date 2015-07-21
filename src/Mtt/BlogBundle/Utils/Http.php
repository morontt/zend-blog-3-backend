<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.07.15
 * Time: 0:00
 */

namespace Mtt\BlogBundle\Utils;

class Http
{
    /**
     * @return mixed
     */
    public static function getClientIp()
    {
        if (self::getServer('HTTP_CLIENT_IP') != null) {
            $ip = self::getServer('HTTP_CLIENT_IP');
        } elseif (self::getServer('HTTP_X_FORWARDED_FOR') != null) {
            $ip = self::getServer('HTTP_X_FORWARDED_FOR');
        } else {
            $ip = self::getServer('REMOTE_ADDR');
        }

        return $ip;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public static function getServer($key)
    {
        return (isset($_SERVER[$key])) ? $_SERVER[$key] : null;
    }
}
