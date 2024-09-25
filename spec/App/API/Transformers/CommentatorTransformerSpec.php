<?php

namespace spec\App\API\Transformers;

use App\API\Transformers\CommentatorTransformer;
use App\Entity\Commentator;
use App\Entity\ViewCommentator;
use App\Entity\User;
use PhpSpec\ObjectBehavior;
use ReflectionClass;

class CommentatorTransformerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CommentatorTransformer::class);
    }

    public function it_convert_commentator()
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

        $this->transform($commentator)->shouldReturn([
            'id' => 27,
            'name' => 'test-name',
            'email' => 'commentator@example.org',
            'website' => 'http://example.org',
            'imageHash' => 'ZQD5TM',
            'isMale' => true,
        ]);
    }

    public function it_convert_female_commentator()
    {
        $reflectionClass = new ReflectionClass(Commentator::class);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $commentator = new Commentator();
        $commentator
            ->setName('test-name')
            ->setEmail('commentator@example.org')
            ->setWebsite('http://example.org')
            ->setGender(User::FEMALE)
        ;

        $reflectionProperty->setValue($commentator, 27);

        $this->transform($commentator)->shouldReturn([
            'id' => 27,
            'name' => 'test-name',
            'email' => 'commentator@example.org',
            'website' => 'http://example.org',
            'imageHash' => '04RETW',
            'isMale' => false,
        ]);
    }

    public function it_convert_view_commentator()
    {
        $reflectionClass = new ReflectionClass(ViewCommentator::class);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $genderProperty = $reflectionClass->getProperty('gender');
        $genderProperty->setAccessible(true);
        $nameProperty = $reflectionClass->getProperty('name');
        $nameProperty->setAccessible(true);

        $commentator = new ViewCommentator();

        $idProperty->setValue($commentator, 27);
        $genderProperty->setValue($commentator, User::MALE);
        $nameProperty->setValue($commentator, 'Pupkin');

        $this->transform($commentator)->shouldReturn([
            'id' => 27,
            'name' => 'Pupkin',
            'email' => null,
            'website' => null,
            'imageHash' => 'ZQD5TM',
            'isMale' => true,
        ]);
    }

    public function it_convert_virtual_user()
    {
        $reflectionClass = new ReflectionClass(ViewCommentator::class);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $genderProperty = $reflectionClass->getProperty('gender');
        $genderProperty->setAccessible(true);
        $nameProperty = $reflectionClass->getProperty('name');
        $nameProperty->setAccessible(true);

        $commentator = new ViewCommentator();

        $idProperty->setValue($commentator, 10000048);
        $genderProperty->setValue($commentator, User::MALE);
        $nameProperty->setValue($commentator, 'Admin');

        $this->transform($commentator)->shouldReturn([
            'id' => 10000048,
            'name' => 'Admin',
            'email' => null,
            'website' => null,
            'imageHash' => '0WMMUN',
            'isMale' => true,
        ]);
    }
}
