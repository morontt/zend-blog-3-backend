<?php

namespace App\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

class RottenLink
{
    public static function doesWork(string $link): bool
    {
        $validDNS = self::checkDns($link);
        if (!$validDNS) {
            return false;
        }

        $httpClient = new Client(['base_uri' => $link]);
        try {
            $response = $httpClient->request(
                'GET',
                '',
                [
                    'headers' => [
                        'Upgrade-Insecure-Requests' => '1',
                        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
                        'Sec-Ch-Ua' => '"Google Chrome";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
                        'Sec-Ch-Ua-Platform' => '"Linux"',
                    ],
                ]
            );
            $status = $response->getStatusCode();
        } catch (GuzzleException $e) {
            $status = 400;
        }

        return $status === 200;
    }

    private static function checkDns(string $link): bool
    {
        $matches = [];
        if (preg_match('/^https?:\/\/([^\/]+)/', $link, $matches)) {
            $host = $matches[1];
            try {
                $records = dns_get_record($host, DNS_A | DNS_CNAME | DNS_AAAA);
            } catch (Throwable $e) {
                return false;
            }

            return count($records) > 0;
        }

        return false;
    }
}
