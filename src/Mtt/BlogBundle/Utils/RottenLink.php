<?php

namespace Mtt\BlogBundle\Utils;

class RottenLink
{
    public static function doesWork(string $link): bool
    {
        $matches = [];
        if (preg_match('/^https?:\/\/([^\/]+)/', $link, $matches)) {
            $host = $matches[1];
            try {
                $records = dns_get_record($host, DNS_A | DNS_CNAME | DNS_AAAA);
            } catch (\Throwable $e) {
                return false;
            }

            return count($records) > 0;
        }

        return false;
    }
}
