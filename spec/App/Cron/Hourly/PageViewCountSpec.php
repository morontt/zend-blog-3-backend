<?php

namespace spec\App\Cron\Hourly;

use App\Cron\Hourly\PageViewCount;
use App\Service\SystemParametersStorage;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;

class PageViewCountSpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $em, SystemParametersStorage $paramStorage)
    {
        $this->beConstructedWith($em, $paramStorage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PageViewCount::class);
    }

    public function it_merge()
    {
        $a = [
            'ID1' => 1,
            'ID2' => 2,
        ];
        $b = [
            'ID2' => 3,
            'ID3' => 4,
        ];

        $this->merge($a, $b)->shouldHaveKeyWithValue('ID1', 1);
        $this->merge($a, $b)->shouldHaveKeyWithValue('ID2', 5);
        $this->merge($a, $b)->shouldHaveKeyWithValue('ID3', 4);

        $this->merge($a, [])->shouldHaveCount(2);
        $this->merge($a, [])->shouldHaveKeyWithValue('ID1', 1);
        $this->merge($a, [])->shouldHaveKeyWithValue('ID2', 2);

        $this->merge([], $b)->shouldHaveCount(2);
        $this->merge([], $b)->shouldHaveKeyWithValue('ID2', 3);
        $this->merge([], $b)->shouldHaveKeyWithValue('ID3', 4);
    }
}
