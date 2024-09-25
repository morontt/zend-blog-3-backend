<?php

namespace spec\App\Service;

use App\Service\SystemParametersStorage;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;

class SystemParametersStorageSpec extends ObjectBehavior
{
    public function let(EntityManager $em)
    {
        $this->beConstructedWith($em, md5('ABC'));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SystemParametersStorage::class);
    }

    public function it_encrypt()
    {
        $this->encrypt('Hello world :)')->shouldReturn('cC9sc1Z0LzVablNJTEtoa3FScz0=');
    }

    public function it_decrypt()
    {
        $this->decrypt('dVBSdkd0bXFNVytTSmI0aHJBPT0=')->shouldReturn('Who is there?');
    }
}
