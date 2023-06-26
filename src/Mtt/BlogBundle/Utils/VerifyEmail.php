<?php

namespace Mtt\BlogBundle\Utils;

/**
 * Only check MX records
 */
class VerifyEmail
{
    private static $domains = [];

    public static function check(string $email): bool
    {
        $domain = self::getDomain($email);
        if (isset(self::$domains[$domain])) {
            return self::$domains[$domain];
        }

        $mxHosts = [];
        getmxrr($domain, $mxHosts);

        $isValid = !empty($mxHosts);
        self::$domains[$domain] = $isValid;

        return $isValid;
    }

    private static function getDomain(string $email)
    {
        $email_arr = explode('@', $email);
        $domain = array_slice($email_arr, -1);

        return $domain[0];
    }
}
