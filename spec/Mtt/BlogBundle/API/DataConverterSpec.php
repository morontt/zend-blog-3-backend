<?php

namespace spec\Mtt\BlogBundle\API;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Tag;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DataConverterSpec extends ObjectBehavior
{
    function let(EntityManager $em)
    {
        $this->beConstructedWith($em);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Mtt\BlogBundle\API\DataConverter');
    }

    function it_is_get_tag()
    {
        $tag = new Tag();
        $tag
            ->setName('test-name')
            ->setUrl('test-url')
        ;

        $this->getTag($tag)->shouldReturn([
            'tag' => [
                'id' => null,
                'name' => 'test-name',
                'url' => 'test-url',
            ],
        ]);

        $tag2 = new Tag();
        $tag2
            ->setName('test2-name')
            ->setUrl('test2-url')
        ;

        $this->getTagArray([$tag, $tag2])->shouldReturn([
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
            ]
        ]);
    }

    function it_is_get_category()
    {
        $category = new Category();
        $category
            ->setName('test-name')
            ->setUrl('test-url')
        ;

        $this->getCategory($category)->shouldReturn([
            'category' => [
                'id' => null,
                'name' => 'test-name',
                'url' => 'test-url',
                'parent' => null,
                'parentId' => null,
            ],
        ]);

        $category2 = new Category();
        $category2
            ->setName('test2-name')
            ->setUrl('test2-url')
        ;

        $this->getCategoryArray([$category, $category2])->shouldReturn([
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
            ]
        ]);
    }

    public function it_is_get_commentator()
    {
        $commentator = new Commentator();
        $commentator
            ->setName('test-name')
            ->setMail('commentator@example.org')
            ->setWebsite('http://example.org')
            ->setDisqusId(0)
            ->setEmailHash(md5('commentator@example.org'))
        ;

        $this->getCommentator($commentator)->shouldReturn([
            'commentator' => [
                'id' => null,
                'name' => 'test-name',
                'email' => 'commentator@example.org',
                'website' => 'http://example.org',
                'disqusId' => 0,
                'emailHash' => md5('commentator@example.org'),
            ],
        ]);

        $commentator2 = new Commentator();
        $commentator2
            ->setName('test2-name')
            ->setMail('two@example.org')
            ->setWebsite('http://example.com')
            ->setDisqusId(55)
            ->setEmailHash(md5('two@example.org'))
        ;

        $this->getCommentatorArray([$commentator, $commentator2])->shouldReturn([
            'commentators' => [
                [
                    'id' => null,
                    'name' => 'test-name',
                    'email' => 'commentator@example.org',
                    'website' => 'http://example.org',
                    'disqusId' => 0,
                    'emailHash' => md5('commentator@example.org'),
                ],
                [
                    'id' => null,
                    'name' => 'test2-name',
                    'email' => 'two@example.org',
                    'website' => 'http://example.com',
                    'disqusId' => 55,
                    'emailHash' => md5('two@example.org'),
                ],
            ]
        ]);
    }

    public function it_is_get_comment()
    {
        $comment = new Comment();
        $comment
            ->setText('Тестовый комментарий')
            ->setIpAddress('94.231.112.91')
            ->setDisqusId(74)
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-02-28 01:30:49'))
        ;

        $this->getComment($comment)->shouldReturn([
            'comment' => [
                'id' => null,
                'text' => 'Тестовый комментарий',
                'commentator' => null,
                'ipAddr' => '94.231.112.91',
                'disqusId' => 74,
                'createdAt' => '2016-02-28T01:30:49+0200',
            ],
            'commentators' => [],
        ]);

        $comment2 = new Comment();
        $comment2
            ->setText('йцук фыва олдж')
            ->setIpAddress('62.72.188.111')
            ->setDisqusId(0)
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-02-28 01:43:14'))
        ;

        $commentator = new Commentator();
        $commentator
            ->setName('test-name')
            ->setMail('commentator@example.org')
            ->setWebsite('http://example.org')
            ->setDisqusId(0)
            ->setEmailHash(md5('commentator@example.org'))
        ;

        $comment2->setCommentator($commentator);

        $this->getCommentArray([$comment, $comment2])->shouldReturn([
            'comments' => [
                [
                    'id' => null,
                    'text' => 'Тестовый комментарий',
                    'commentator' => null,
                    'ipAddr' => '94.231.112.91',
                    'disqusId' => 74,
                    'createdAt' => '2016-02-28T01:30:49+0200',
                ],
                [
                    'id' => null,
                    'text' => 'йцук фыва олдж',
                    'commentator' => null,
                    'ipAddr' => '62.72.188.111',
                    'disqusId' => 0,
                    'createdAt' => '2016-02-28T01:43:14+0200',
                ]
            ],
            'commentators' => [
                [
                    'id' => null,
                    'name' => 'test-name',
                    'email' => 'commentator@example.org',
                    'website' => 'http://example.org',
                    'disqusId' => 0,
                    'emailHash' => md5('commentator@example.org'),
                ],
            ]
        ]);
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
        ;

        $this->getPost($post, 'category')->shouldReturn([
            'post' => [
                'id' => null,
                'title' => 'ещё о PHP',
                'url' => 'esce-o-php',
                'category' => null,
                'categoryId' => null,
                'hidden' => false,
                'text' => '<p>Ещё одна запись о PHP</p>',
                'description' => 'description PHP',
                'tagsString' => '',
                'timeCreated' => '2016-02-07T22:40:24+0200',
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
        ]);

        $post2 = new Post();
        $post2
            ->setTitle('Тестовая запись')
            ->setUrl('testovaya-zapis')
            ->setCategory($category)
            ->setHide(false)
            ->setRawText('<p>Тестовая запись, собственно...</p>')
            ->setDescription('метатег description')
            ->setTimeCreated(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-11 01:05:33'))
        ;

        //TODO duplicate categories
        $this->getPostArray([$post, $post2], 'category')->shouldReturn([
            'posts' => [
                [
                    'id' => null,
                    'title' => 'ещё о PHP',
                    'url' => 'esce-o-php',
                    'category' => null,
                    'categoryId' => null,
                    'hidden' => false,
                    'text' => '<p>Ещё одна запись о PHP</p>',
                    'description' => 'description PHP',
                    'tagsString' => '',
                    'timeCreated' => '2016-02-07T22:40:24+0200',
                ],
                [
                    'id' => null,
                    'title' => 'Тестовая запись',
                    'url' => 'testovaya-zapis',
                    'category' => null,
                    'categoryId' => null,
                    'hidden' => false,
                    'text' => '<p>Тестовая запись, собственно...</p>',
                    'description' => 'метатег description',
                    'tagsString' => '',
                    'timeCreated' => '2016-01-11T01:05:33+0200',
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
        ]);
    }
}
