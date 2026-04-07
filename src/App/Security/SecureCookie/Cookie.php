<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 06.04.26
 * Time: 07:23
 */

namespace App\Security\SecureCookie;

use App\DTO\SecureCookieDTO;
use App\LogTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

use function App\Utils\base64url_decode;

class Cookie
{
    use LogTrait;

    private const CIPHER = 'DES-EDE3-CBC';

    private string $cipherKey;

    public function __construct(
        private SerializerInterface $serializer,
        private string $hashKey,
        string $blockKey,
        LoggerInterface $logger,
    ) {
        $this->cipherKey = substr(hash('sha224', $blockKey, true), 0, 24);
        $this->setLogger($logger);
    }

    public function decode(string $data): ?SecureCookieDTO
    {
        $binaryData = base64url_decode($data);
        if ($binaryData === false) {
            $this->error('Invalid base64 data', ['data' => $data]);

            return null;
        }

        if (!$this->verifyMac($binaryData)) {
            $this->error('Invalid HMAC digest', ['data' => $data]);

            return null;
        }

        $decrypted = $this->decrypt($binaryData);
        if ($decrypted === false) {
            $this->error('Invalid encrypted data', ['data' => $data]);

            return null;
        }

        return $this->serializer->deserialize($decrypted, SecureCookieDTO::class, 'json');
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

    public function decrypt(string $str): string|false
    {
        $iv = substr($str, 0, openssl_cipher_iv_length(self::CIPHER));
        $data = substr($str, openssl_cipher_iv_length(self::CIPHER));

        return openssl_decrypt(substr($data, 0, -28), self::CIPHER, $this->cipherKey, OPENSSL_RAW_DATA, $iv);
    }
}
