<?php

namespace spec\App\Telegram\Command;

use App\Telegram\Command\Uptime;
use PhpSpec\ObjectBehavior;

class UptimeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Uptime::class);
    }

    public function it_is_get_name()
    {
        $this->getCommandName()->shouldReturn('uptime');
    }
}
