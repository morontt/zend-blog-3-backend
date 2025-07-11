<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 07.10.17
 * Time: 11:04
 */

namespace App\Service;

use App\Entity\SystemParameters;
use App\Repository\SystemParametersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;

class SystemParametersStorage
{
    public const CIPHER = 'DES-EDE3-OFB';

    /**
     * @var SystemParametersRepository
     */
    protected $parametersRepo;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     * @param string $secret
     */
    public function __construct(EntityManagerInterface $em, string $secret)
    {
        $this->parametersRepo = $em->getRepository(SystemParameters::class);
        $this->em = $em;

        $this->secret = $secret;
    }

    /**
     * @param string $key
     * @param string $value
     * @param bool $encrypted
     *
     * @throws ORMException
     */
    public function saveParameter(string $key, string $value, bool $encrypted = false)
    {
        $sp = $this->parametersRepo->findOneByOptionKey($key);

        if (!$sp) {
            $sp = new SystemParameters();
            $sp->setOptionKey($key);
            $this->em->persist($sp);
        }

        $sp->setValue($encrypted ? $this->encrypt($value) : $value)
            ->setEncrypted($encrypted);
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
