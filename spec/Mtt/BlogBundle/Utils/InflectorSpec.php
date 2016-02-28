<?php

namespace spec\Mtt\BlogBundle\Utils;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InflectorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Mtt\BlogBundle\Utils\Inflector');
    }

    function it_is_blank_string()
    {
        $this->pluralize('')->shouldReturn('');
        $this->pluralize('  ')->shouldReturn('  ');
    }

    function it_is_uncountable_word()
    {
        $this->pluralize('money')->shouldReturn('money');
        $this->pluralize('fat-sheep')->shouldReturn('fat-sheep');
        $this->pluralize('blowFish')->shouldReturn('blowFish');
    }

    function it_is_irregular_word()
    {
        $this->pluralize('uglyMan')->shouldReturn('uglyMen');
        $this->pluralize('cow')->shouldReturn('kine');
        $this->pluralize('tricky-zombie')->shouldReturn('tricky-zombies');
    }

    function it_is_plurals_word()
    {
        $this->pluralize('category')->shouldReturn('categories');
        $this->pluralize('stupidFace')->shouldReturn('stupidFaces');
        $this->pluralize('small-country')->shouldReturn('small-countries');
        $this->pluralize('knife')->shouldReturn('knives');
        $this->pluralize('advice')->shouldReturn('advices');
        $this->pluralize('bigFormula')->shouldReturn('bigFormulas');
        $this->pluralize('key')->shouldReturn('keys');
        $this->pluralize('box')->shouldReturn('boxes');
        $this->pluralize('DrunkOctopus')->shouldReturn('DrunkOctopi');
    }
}
