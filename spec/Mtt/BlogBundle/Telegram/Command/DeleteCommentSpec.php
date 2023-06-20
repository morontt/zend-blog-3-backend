<?php

namespace spec\Mtt\BlogBundle\Telegram\Command;

use Mtt\BlogBundle\Entity\Repository\CommentRepository;
use Mtt\BlogBundle\Telegram\Command\DeleteComment;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DeleteCommentSpec extends ObjectBehavior
{
    public function let(CommentRepository $repo, EventDispatcherInterface $dispatcher)
    {
        $this->beConstructedWith($repo, $dispatcher);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DeleteComment::class);
    }

    public function it_is_get_name()
    {
        $this->getCommandName()->shouldReturn('deletecomment');
    }
}
