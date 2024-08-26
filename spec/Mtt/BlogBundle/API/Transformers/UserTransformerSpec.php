<?php

namespace spec\Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\API\Transformers\UserTransformer;
use Mtt\UserBundle\Entity\User;
use PhpSpec\ObjectBehavior;
use ReflectionClass;

class UserTransformerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserTransformer::class);
    }

    public function it_convert_user()
    {
        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $user = new User();
        $user
            ->setUsername('test-name')
            ->setEmail('user@example.org')
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2024-08-11 11:30:28'))
        ;

        $reflectionProperty->setValue($user, 175);

        $this->transform($user)->shouldReturn([
            'id' => 175,
            'username' => 'test-name',
            'displayName' => null,
            'email' => 'user@example.org',
            'role' => 'guest',
            'imageHash' => '037JC4',
            'isMale' => true,
            'createdAt' => '2024-08-11T11:30:28+03:00',
        ]);
    }

    public function it_convert_user_female()
    {
        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $user = new User();
        $user
            ->setUsername('Helga')
            ->setEmail('helga@example.org')
            ->setGender(User::FEMALE)
            ->setDisplayName('Хельга')
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2024-08-11 11:28:39'))
        ;

        $reflectionProperty->setValue($user, 176);

        $this->transform($user)->shouldReturn([
            'id' => 176,
            'username' => 'Helga',
            'displayName' => 'Хельга',
            'email' => 'helga@example.org',
            'role' => 'guest',
            'imageHash' => 'ZMXNUD',
            'isMale' => false,
            'createdAt' => '2024-08-11T11:28:39+03:00',
        ]);

        $user->setAvatarVariant(1);
        $this->transform($user)->shouldReturn([
            'id' => 176,
            'username' => 'Helga',
            'displayName' => 'Хельга',
            'email' => 'helga@example.org',
            'role' => 'guest',
            'imageHash' => 'R7WTVY',
            'isMale' => false,
            'createdAt' => '2024-08-11T11:28:39+03:00',
        ]);
    }
}
