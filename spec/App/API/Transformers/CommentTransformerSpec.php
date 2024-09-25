<?php

namespace spec\App\API\Transformers;

use App\API\Transformers\CommentTransformer;
use App\Entity\Comment;
use App\Entity\Commentator;
use App\Entity\User;
use App\Entity\ViewComment;
use PhpSpec\ObjectBehavior;
use ReflectionClass;

class CommentTransformerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CommentTransformer::class);
    }

    public function it_convert_with_commentator()
    {
        $reflectionClass = new ReflectionClass(Commentator::class);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $commentator = new Commentator();
        $commentator
            ->setName('test-name')
            ->setEmail('commentator@example.org')
            ->setWebsite('http://example.org')
        ;

        $reflectionProperty->setValue($commentator, 27);

        $comment = new Comment();
        $comment
            ->setText('Тестовый комментарий')
            ->setIpAddress('94.231.112.91')
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-02-28 01:30:49'))
            ->setCommentator($commentator)
        ;

        $this->transform($comment)->shouldReturn([
            'id' => null,
            'text' => 'Тестовый комментарий',
            'commentator' => 27,
            'commentatorId' => 27,
            'username' => 'test-name',
            'email' => 'commentator@example.org',
            'website' => 'http://example.org',
            'ipAddr' => '94.231.112.91',
            'city' => null,
            'region' => null,
            'country' => null,
            'countryFlag' => '',
            'parent' => null,
            'imageHash' => 'ZQD5TM',
            'deleted' => false,
            'userAgent' => null,
            'bot' => false,
            'createdAt' => '2016-02-28T01:30:49+03:00',
        ]);
    }

    public function it_convert_with_view_commentator()
    {
        $reflectionClass = new ReflectionClass(ViewComment::class);
        $uidProperty = $reflectionClass->getProperty('virtualUserId');
        $uidProperty->setAccessible(true);
        $usernameProperty = $reflectionClass->getProperty('username');
        $usernameProperty->setAccessible(true);
        $textProperty = $reflectionClass->getProperty('text');
        $textProperty->setAccessible(true);
        $createdProperty = $reflectionClass->getProperty('timeCreated');
        $createdProperty->setAccessible(true);

        $comment = new ViewComment();

        $uidProperty->setValue($comment, 27);
        $usernameProperty->setValue($comment, 'test-name');
        $textProperty->setValue($comment, 'Lorem ipsum');
        $createdProperty->setValue($comment, \DateTime::createFromFormat('Y-m-d H:i:s', '2023-06-27 14:21:09'));

        $this->transform($comment)->shouldReturn([
            'id' => null,
            'text' => 'Lorem ipsum',
            'commentator' => 27,
            'commentatorId' => 27,
            'username' => 'test-name',
            'email' => null,
            'website' => null,
            'ipAddr' => null,
            'city' => null,
            'region' => null,
            'country' => null,
            'countryFlag' => '',
            'parent' => null,
            'imageHash' => 'ZQD5TM',
            'deleted' => false,
            'userAgent' => null,
            'bot' => false,
            'createdAt' => '2023-06-27T14:21:09+03:00',
        ]);
    }

    public function it_convert_with_user()
    {
        $reflectionClass = new ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $user = new User();
        $user
            ->setUsername('Admin')
            ->setEmail('admin@example.org')
        ;

        $reflectionProperty->setValue($user, 48);

        $comment = new Comment();
        $comment
            ->setText('Тестовый комментарий')
            ->setIpAddress('94.231.112.91')
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2023-02-28 01:30:49'))
            ->setUser($user)
        ;

        $this->transform($comment)->shouldReturn([
            'id' => null,
            'text' => 'Тестовый комментарий',
            'commentator' => 10000048,
            'commentatorId' => 10000048,
            'username' => 'Admin',
            'email' => 'admin@example.org',
            'website' => null,
            'ipAddr' => '94.231.112.91',
            'city' => null,
            'region' => null,
            'country' => null,
            'countryFlag' => '',
            'parent' => null,
            'imageHash' => '0WMMUN',
            'deleted' => false,
            'userAgent' => null,
            'bot' => false,
            'createdAt' => '2023-02-28T01:30:49+03:00',
        ]);
    }
}
