<?php

namespace spec\App\Telegram\Command;

use App\Repository\CommentRepository;
use App\Service\CommentManager;
use App\Telegram\Command\AnswerComment;
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
