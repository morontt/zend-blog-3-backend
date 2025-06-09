<?php

namespace App\Utils;

/**
 * Only check MX records
 */
class VerifyEmail
{
    /** @var array<string, bool> */
    private static array $domains = [];

    public static function isValid(string $email): bool
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

    public static function normalize(string $email): string
    {
        return strtolower(trim($email));
    }

    private static function getDomain(string $email): string
    {
        $email_arr = explode('@', $email);
        $domain = array_slice($email_arr, -1);

        return $domain[0];
    }
}
