<?php

namespace App\Service;

use App\DTO\ExternalUserDTO;
use App\DTO\UserByExternalDTO;
use App\Entity\User;
use App\Entity\UserExtraInfo;
use App\Exception\ShortPasswordException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    /**
     * @var EntityManager
     */
    private $em;

    private UserPasswordHasherInterface $passwordHasher;

    private Tracking $tracking;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        Tracking $tracking
    ) {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
        $this->tracking = $tracking;
    }

    public function findByExternalDTO(ExternalUserDTO $data): UserByExternalDTO
    {
        $infoRepository = $this->em->getRepository(UserExtraInfo::class);
        $userInfo = $infoRepository->findOneBy([
            'externalId' => $data->id,
            'dataProvider' => $data->dataProvider,
        ]);

        if ($userInfo) {
            return new UserByExternalDTO($userInfo->getUser(), true);
        }

        if (!empty($data->email)) {
            $userRepository = $this->em->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $data->email]);
            if ($user) {
                return new UserByExternalDTO($user, false);
            }
        }

        return new UserByExternalDTO(null, false);
    }

    public function createFromExternalDTO(ExternalUserDTO $data): User
    {
        $generatedUsername = $this->randomUsername();
        $userRepository = $this->em->getRepository(User::class);

        $username = $data->username;

        if (empty($username)) {
            $username = $generatedUsername;
        } else {
            $temporaryUser = $userRepository->findOneByUsername($username);
            if ($temporaryUser) {
                $username = $generatedUsername;
            }
        }

        $email = $data->email;
        if (empty($email)) {
            $email = User::fakeEmail($generatedUsername);
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

    /**
     * @param string $username
     * @param string $email
     * @param string|null $password
     *
     * @return User
     */
    public function createUser(string $username, string $email, ?string $password = null): User
    {
        if (is_null($password)) {
            try {
                $password = base64_encode(random_bytes(32));
            } catch (Exception $exception) {
                $password = hash('sha256', uniqid(mt_rand(), true));
            }
        }

        $user = new User();
        $user
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($this->passwordHasher->hashPassword($user, $password))
        ;

        return $user;
    }

    /**
     * @param User $user
     * @param string $password
     *
     * @throws ShortPasswordException
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function updatePassword(User $user, string $password): void
    {
        if (strlen($password) <= 4) {
            throw new ShortPasswordException('Password too short');
        }

        $user->setRandomSalt();
        $user->setRandomWsseKey();

        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

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

    private function randomUsername(): string
    {
        $repository = $this->em->getRepository(User::class);
        do {
            $random = 'ext-' . str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            $obj = $repository->getByRandomName($random);
        } while ($obj !== null);

        return $random;
    }
}
