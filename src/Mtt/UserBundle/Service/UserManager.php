<?php

namespace Mtt\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Mtt\BlogBundle\DTO\ExternalUserDTO;
use Mtt\UserBundle\Entity\User;
use Mtt\UserBundle\Exception\ShortPasswordException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(
        EntityManagerInterface $em,
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->em = $em;
        $this->encoderFactory = $encoderFactory;
    }

    public function createFromExternalDTO(ExternalUserDTO $dataObj): User
    {
        // external ID and provider are primary
        // email after (if email is empty - generate)
        // username - check if exists and generate

        $username = $dataObj->username;
        $email = $dataObj->email;

        return $this->createUser($username, $email);
    }

    public function createUser(string $username, string $email, string $password = null): User
    {
        if (is_null($password)) {
            try {
                $password = base64_encode(random_bytes(32));
            } catch (Exception $exception) {
                $password = hash('sha256', uniqid(mt_rand(), true));
            }
        }

        $user = new User();
        $encoder = $this->encoderFactory->getEncoder($user);

        $passwordHash = $encoder->encodePassword($password, $user->getSalt());
        $user
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($passwordHash)
        ;

        return $user;
    }

    public function updatePassword(User $user, string $password): void
    {
        if (strlen($password) <= 4) {
            throw new ShortPasswordException('Password too short');
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        $user->setRandomSalt();
        $user->setRandomWsseKey();

        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $this->em->persist($user);
        $this->em->flush();
    }
}
