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
    private const CIPHER = 'DES-EDE3-CBC';

    private string $cipherKey;

    public function __construct(
        private string $hashKey,
        string $blockKey,
    ) {
        $this->cipherKey = substr(hash('sha224', $blockKey, true), 0, 24);
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

    public function decrypt(string $str): string
    {
        $iv = substr($str, 0, openssl_cipher_iv_length(self::CIPHER));
        $data = substr($str, openssl_cipher_iv_length(self::CIPHER));

        return openssl_decrypt(substr($data, 0, -28), self::CIPHER, $this->cipherKey, OPENSSL_RAW_DATA, $iv);
    }
}
