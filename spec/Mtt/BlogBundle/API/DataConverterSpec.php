<?php

namespace spec\Mtt\BlogBundle\API;

use Doctrine\ORM\EntityManager;
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

        $this->getTagsArray([$tag, $tag2])->shouldReturn([
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
}
