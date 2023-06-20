<?php

namespace spec\Mtt\BlogBundle\Telegram\Command;

use Mtt\BlogBundle\Entity\Repository\CommentRepository;
use Mtt\BlogBundle\Service\CommentManager;
use Mtt\BlogBundle\Telegram\Command\AnswerComment;
use PhpSpec\ObjectBehavior;

class AnswerCommentSpec extends ObjectBehavior
{
    public function let(CommentRepository $repo, CommentManager $commentManager)
    {
        $this->beConstructedWith($repo, $commentManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AnswerComment::class);
    }

    public function it_is_get_name()
    {
        $this->getCommandName()->shouldReturn('answer');
    }
}
