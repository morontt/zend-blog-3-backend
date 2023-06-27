<?php

namespace spec\Mtt\BlogBundle\API;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\API\DataConverter;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\GeoLocation;
use Mtt\BlogBundle\Entity\GeoLocationCity;
use Mtt\BlogBundle\Entity\GeoLocationCountry;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Repository\CategoryRepository;
use Mtt\BlogBundle\Entity\Repository\CommentRepository;
use Mtt\BlogBundle\Entity\Tag;
use Mtt\BlogBundle\Service\TextProcessor;
use PhpSpec\ObjectBehavior;
use ReflectionClass;

class DataConverterSpec extends ObjectBehavior
{
    public function let(EntityManager $em, TextProcessor $tp, CommentRepository $cmr, CategoryRepository $cr)
    {
        $this->beConstructedWith($em, $tp, $cmr, $cr);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DataConverter::class);
    }

    public function it_is_get_tag()
    {
        $tag = new Tag();
        $tag
            ->setName('test-name')
            ->setUrl('test-url')
        ;

        $this->getTag($tag)->shouldReturn(
            [
                'tag' => [
                    'id' => null,
                    'name' => 'test-name',
                    'url' => 'test-url',
                ],
            ]
        );

        $tag2 = new Tag();
        $tag2
            ->setName('test2-name')
            ->setUrl('test2-url')
        ;

        $this->getTagArray([$tag, $tag2])->shouldReturn(
            [
                'tags' => [
                    [
                        'id' => null,
                        'name' => 'test-name',
                        'url' => 'test-url',
                    ],
                    [
                        'id' => null,
                        'name' => 'test2-name',
                        'url' => 'test2-url',
                    ],
                ],
            ]
        );
    }

    public function it_is_get_category()
    {
        $category = new Category();
        $category
            ->setName('test-name')
            ->setUrl('test-url')
        ;

        $this->getCategory($category)->shouldReturn(
            [
                'category' => [
                    'id' => null,
                    'name' => 'test-name',
                    'url' => 'test-url',
                    'parent' => null,
                    'parentId' => null,
                ],
            ]
        );

        $category2 = new Category();
        $category2
            ->setName('test2-name')
            ->setUrl('test2-url')
        ;

        $this->getCategoryArray([$category, $category2])->shouldReturn(
            [
                'categories' => [
                    [
                        'id' => null,
                        'name' => 'test-name',
                        'url' => 'test-url',
                        'parent' => null,
                        'parentId' => null,
                    ],
                    [
                        'id' => null,
                        'name' => 'test2-name',
                        'url' => 'test2-url',
                        'parent' => null,
                        'parentId' => null,
                    ],
                ],
            ]
        );
    }

    public function it_is_get_commentator()
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

        $reflectionProperty->setValue($commentator, 13);

        $this->getCommentator($commentator)->shouldReturn(
            [
                'commentator' => [
                    'id' => 13,
                    'name' => 'test-name',
                    'email' => 'commentator@example.org',
                    'website' => 'http://example.org',
                    'imageHash' => 'A9GSDZ',
                    'isMale' => true,
                ],
            ]
        );

        $commentator2 = new Commentator();
        $commentator2
            ->setName('test2-name')
            ->setEmail('two@example.org')
            ->setWebsite('http://example.com')
            ->setGender(Commentator::FEMALE)
        ;

        $reflectionProperty->setValue($commentator2, 72);

        $this->getCommentatorArray([$commentator, $commentator2])->shouldReturn(
            [
                'commentators' => [
                    [
                        'id' => 13,
                        'name' => 'test-name',
                        'email' => 'commentator@example.org',
                        'website' => 'http://example.org',
                        'imageHash' => 'A9GSDZ',
                        'isMale' => true,
                    ],
                    [
                        'id' => 72,
                        'name' => 'test2-name',
                        'email' => 'two@example.org',
                        'website' => 'http://example.com',
                        'imageHash' => '07XXUP',
                        'isMale' => false,
                    ],
                ],
            ]
        );
    }

    public function it_is_get_comment()
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

        $reflectionProperty->setValue($commentator, 13);

        $comment = new Comment();
        $comment
            ->setText('Тестовый комментарий')
            ->setIpAddress('94.231.112.91')
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-02-28 01:30:49'))
            ->setCommentator($commentator)
        ;

        $this->getComment($comment)->shouldReturn(
            [
                'comment' => [
                    'id' => null,
                    'text' => 'Тестовый комментарий',
                    'commentator' => 13,
                    'commentatorId' => 13,
                    'username' => 'test-name',
                    'email' => 'commentator@example.org',
                    'website' => 'http://example.org',
                    'ipAddr' => '94.231.112.91',
                    'city' => null,
                    'region' => null,
                    'country' => null,
                    'countryFlag' => '',
                    'parent' => null,
                    'imageHash' => 'A9GSDZ',
                    'deleted' => false,
                    'userAgent' => null,
                    'bot' => false,
                    'createdAt' => '2016-02-28T01:30:49+03:00',
                ],
            ]
        );

        $comment2 = new Comment();
        $comment2
            ->setText('йцук фыва олдж')
            ->setIpAddress('62.72.188.111')
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-02-28 01:43:14'))
        ;

        $location = new GeoLocation();
        $city = new GeoLocationCity();
        $country = new GeoLocationCountry();

        $country->setName('Germany');

        $city
            ->setCity('Frankfurt am Main')
            ->setRegion('Hessen')
            ->setCountry($country)
        ;

        $location
            ->setCity($city)
            ->setIpAddress('62.72.188.111')
        ;

        $comment2->setGeoLocation($location);

        $commentator = new Commentator();
        $commentator
            ->setName('test-name')
            ->setEmail('commentator@example.org')
            ->setWebsite('http://example.org')
        ;

        $reflectionProperty->setValue($commentator, 34);

        $comment2->setCommentator($commentator);

        $this->getCommentArray([$comment, $comment2])->shouldReturn(
            [
                'comments' => [
                    [
                        'id' => null,
                        'text' => 'Тестовый комментарий',
                        'commentator' => 13,
                        'commentatorId' => 13,
                        'username' => 'test-name',
                        'email' => 'commentator@example.org',
                        'website' => 'http://example.org',
                        'ipAddr' => '94.231.112.91',
                        'city' => null,
                        'region' => null,
                        'country' => null,
                        'countryFlag' => '',
                        'parent' => null,
                        'imageHash' => 'A9GSDZ',
                        'deleted' => false,
                        'userAgent' => null,
                        'bot' => false,
                        'createdAt' => '2016-02-28T01:30:49+03:00',
                    ],
                    [
                        'id' => null,
                        'text' => 'йцук фыва олдж',
                        'commentator' => 34,
                        'commentatorId' => 34,
                        'username' => 'test-name',
                        'email' => 'commentator@example.org',
                        'website' => 'http://example.org',
                        'ipAddr' => '62.72.188.111',
                        'city' => 'Frankfurt am Main',
                        'region' => 'Hessen',
                        'country' => 'Germany',
                        'countryFlag' => '',
                        'parent' => null,
                        'imageHash' => 'ZJQ6CD',
                        'deleted' => false,
                        'userAgent' => null,
                        'bot' => false,
                        'createdAt' => '2016-02-28T01:43:14+03:00',
                    ],
                ],
            ]
        );

        $this->getCommentArray([$comment2], 'commentator')->shouldReturn(
            [
                'comments' => [
                    [
                        'id' => null,
                        'text' => 'йцук фыва олдж',
                        'commentator' => 34,
                        'commentatorId' => 34,
                        'username' => 'test-name',
                        'email' => 'commentator@example.org',
                        'website' => 'http://example.org',
                        'ipAddr' => '62.72.188.111',
                        'city' => 'Frankfurt am Main',
                        'region' => 'Hessen',
                        'country' => 'Germany',
                        'countryFlag' => '',
                        'parent' => null,
                        'imageHash' => 'ZJQ6CD',
                        'deleted' => false,
                        'userAgent' => null,
                        'bot' => false,
                        'createdAt' => '2016-02-28T01:43:14+03:00',
                    ],
                ],
                'commentators' => [
                    [
                        'id' => 34,
                        'name' => 'test-name',
                        'email' => 'commentator@example.org',
                        'website' => 'http://example.org',
                        'imageHash' => 'ZJQ6CD',
                        'isMale' => true,
                    ],
                ],
            ]
        );
    }

    public function it_is_get_post()
    {
        $category = new Category();
        $category
            ->setName('PHP')
            ->setUrl('php')
        ;

        $post = new Post();
        $post
            ->setTitle('ещё о PHP')
            ->setUrl('esce-o-php')
            ->setCategory($category)
            ->setHide(false)
            ->setRawText('<p>Ещё одна запись о PHP</p>')
            ->setDescription('description PHP')
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-02-07 22:40:24'))
            ->setLastUpdate(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-02-07 22:40:24'))
        ;

        $this->getPost($post, 'category')->shouldReturn(
            [
                'post' => [
                    'id' => null,
                    'title' => 'ещё о PHP',
                    'url' => 'esce-o-php',
                    'category' => null,
                    'categoryId' => null,
                    'hidden' => false,
                    'disableComments' => false,
                    'text' => '<p>Ещё одна запись о PHP</p>',
                    'description' => 'description PHP',
                    'tagsString' => '',
                    'timeCreated' => '2016-02-07T22:40:24+03:00',
                    'lastUpdate' => '2016-02-07T22:40:24+03:00',
                ],
                'categories' => [
                    [
                        'id' => null,
                        'name' => 'PHP',
                        'url' => 'php',
                        'parent' => null,
                        'parentId' => null,
                    ],
                ],
            ]
        );

        $post2 = new Post();
        $post2
            ->setTitle('Тестовая запись')
            ->setUrl('testovaya-zapis')
            ->setCategory($category)
            ->setHide(false)
            ->setRawText('<p>Тестовая запись, собственно...</p>')
            ->setDescription('метатег description')
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-11 01:05:33'))
            ->setLastUpdate(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-11 01:05:33'))
        ;

        //TODO duplicate categories
        $this->getPostArray([$post, $post2], 'category')->shouldReturn(
            [
                'posts' => [
                    [
                        'id' => null,
                        'title' => 'ещё о PHP',
                        'url' => 'esce-o-php',
                        'category' => null,
                        'categoryId' => null,
                        'hidden' => false,
                        'disableComments' => false,
                        'text' => '<p>Ещё одна запись о PHP</p>',
                        'description' => 'description PHP',
                        'tagsString' => '',
                        'timeCreated' => '2016-02-07T22:40:24+03:00',
                        'lastUpdate' => '2016-02-07T22:40:24+03:00',
                    ],
                    [
                        'id' => null,
                        'title' => 'Тестовая запись',
                        'url' => 'testovaya-zapis',
                        'category' => null,
                        'categoryId' => null,
                        'hidden' => false,
                        'disableComments' => false,
                        'text' => '<p>Тестовая запись, собственно...</p>',
                        'description' => 'метатег description',
                        'tagsString' => '',
                        'timeCreated' => '2016-01-11T01:05:33+03:00',
                        'lastUpdate' => '2016-01-11T01:05:33+03:00',
                    ],
                ],
                'categories' => [
                    [
                        'id' => null,
                        'name' => 'PHP',
                        'url' => 'php',
                        'parent' => null,
                        'parentId' => null,
                    ],
                    [
                        'id' => null,
                        'name' => 'PHP',
                        'url' => 'php',
                        'parent' => null,
                        'parentId' => null,
                    ],
                ],
            ]
        );
    }
}
