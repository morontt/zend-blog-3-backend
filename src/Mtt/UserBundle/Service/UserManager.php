<?php

namespace Mtt\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Mtt\BlogBundle\DTO\ExternalUserDTO;
use Mtt\BlogBundle\Service\Tracking;
use Mtt\UserBundle\Entity\User;
use Mtt\UserBundle\Entity\UserExtraInfo;
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

    /**
     * @var Tracking
     */
    private $tracking;

    public function __construct(
        EntityManagerInterface $em,
        EncoderFactoryInterface $encoderFactory,
        Tracking $tracking
    ) {
        $this->em = $em;
        $this->encoderFactory = $encoderFactory;
        $this->tracking = $tracking;
    }

    public function findByExternalDTO(ExternalUserDTO $data): array
    {
        $infoRepository = $this->em->getRepository(UserExtraInfo::class);
        $userInfo = $infoRepository->findOneBy([
            'externalId' => $data->id,
            'dataProvider' => $data->dataProvider,
        ]);

        if ($userInfo) {
            return [$userInfo->getUser(), true];
        }

        if (!empty($data->email)) {
            $userRepository = $this->em->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $data->email]);
            if ($user) {
                return [$user, false];
            }
        }

        return [null, false];
    }

    public function createFromExternalDTO(ExternalUserDTO $data): User
    {
        $generatedUsername = 'ext-' . str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
        $userRepository = $this->em->getRepository(User::class);

        $username = $data->username;
        $email = $data->email;

        if (empty($username)) {
            $username = $generatedUsername;
        } else {
            $temporaryUser = $userRepository->findOneByUsername($username);
            if ($temporaryUser) {
                $username = $generatedUsername;
            }
        }

        if (empty($email)) {
            $email = $generatedUsername . '@xelbot.fake';
        }

        $user = $this->createUser($username, $email);

        if (!empty($data->displayName)) {
            $user->setDisplayName($data->displayName);
        }

        if (!empty($data->gender) && $data->gender === 'female') {
            $user->setGender(User::FEMALE);
        }

        return $user;
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

    public function saveUserExtraInfo(ExternalUserDTO $data, User $user, ?string $ip, ?string $userAgent): UserExtraInfo
    {
        $agent = $userAgent ? $this->tracking->getTrackingAgent($userAgent) : null;

        $userInfo = new UserExtraInfo();
        $userInfo
            ->setUser($user)
            ->setExternalId($data->id)
            ->setDataProvider($data->dataProvider)
            ->setRawData($data->rawData)
            ->setIpAddress($ip)
            ->setTrackingAgent($agent)
            ->setUsername($data->username)
            ->setFirstName($data->firstName)
            ->setLastName($data->lastName)
            ->setDisplayName($data->displayName)
            ->setEmail($data->email)
            ->setAvatar($data->avatar)
        ;

        if ($data->gender === 'female') {
            $userInfo->setGender(UserExtraInfo::FEMALE);
        } elseif ($data->gender === 'male') {
            $userInfo->setGender(UserExtraInfo::MALE);
        }

        $this->em->persist($userInfo);
        $this->em->flush();

        return $userInfo;
    }
}
