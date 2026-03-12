<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 07.10.17
 * Time: 11:04
 */

namespace App\Service;

use App\Entity\SystemParameters;
use App\Repository\SystemParametersRepository;
use Doctrine\ORM\EntityManagerInterface;

class SystemParametersStorage
{
    public const CIPHER = 'DES-EDE3-OFB';

    public function __construct(
        private EntityManagerInterface $em,
        private SystemParametersRepository $parametersRepo,
        private string $secret,
    ) {
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveParameter(string $key, string $value, bool $encrypted = false): void
    {
        $sp = $this->parametersRepo->findOneByOptionKey($key);

        if (!$sp) {
            $sp = new SystemParameters();
            $sp->setOptionKey($key);
            $this->em->persist($sp);
        }

        $sp
            ->setValue($encrypted ? $this->encrypt($value) : $value)
            ->setEncrypted($encrypted)
        ;

        $this->em->flush();
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function getParameter(string $key): ?string
    {
        $sp = $this->parametersRepo->findOneByOptionKey($key);

        if (!$sp) {
            return null;
        }

        return $sp->isEncrypted() ? $this->decrypt($sp->getValue()) : $sp->getValue();
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function encrypt(string $value): string
    {
        return base64_encode(openssl_encrypt($value, self::CIPHER, $this->secret, 0, $this->getVector()));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function decrypt(string $value): string
    {
        return openssl_decrypt(base64_decode($value), self::CIPHER, $this->secret, 0, $this->getVector());
    }

    /**
     * @return string
     */
    protected function getVector(): string
    {
        return substr(sha1($this->secret), 0, openssl_cipher_iv_length(self::CIPHER));
    }
}
