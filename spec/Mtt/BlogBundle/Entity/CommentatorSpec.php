<?php

namespace spec\Mtt\BlogBundle\Entity;

use Mtt\BlogBundle\Entity\Commentator;
use PhpSpec\ObjectBehavior;

class CommentatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Commentator::class);
    }

    public function it_is_get_avatar_hash()
    {
        $this->setName('Spring');
        $this->getAvatarHash()->shouldReturn(md5('spring'));

        $this->setWebsite('example.ORG');
        $this->getAvatarHash()->shouldReturn(md5(md5('spring') . 'example.org'));

        $this->setEmail('Test@example.org');
        $this->getAvatarHash()->shouldReturn(md5('test@example.org'));
    }
}
