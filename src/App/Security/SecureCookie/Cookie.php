<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 06.04.26
 * Time: 07:23
 */

namespace App\Security\SecureCookie;

class Cookie
{
    public function __construct(
        private string $hashKey,
        string $blockKey,
    ) {
    }

    public function createMac(string $data): string
    {
        return hash_hmac('sha224', $data, $this->hashKey, true);
    }

    public function verifyMac(string $data): bool
    {
        if (strlen($data) < 29) {
            return false;
        }

        return hash_equals(
            substr($data, -28),
            $this->createMac(substr($data, 0, -28))
        );
    }
}
