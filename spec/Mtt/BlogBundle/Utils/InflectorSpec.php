<?php

namespace spec\Mtt\BlogBundle\Utils;

use PhpSpec\ObjectBehavior;

class InflectorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Mtt\BlogBundle\Utils\Inflector');
    }

    public function it_is_blank_string()
    {
        $this->pluralize('')->shouldReturn('');
        $this->pluralize('  ')->shouldReturn('  ');
    }

    public function it_is_uncountable_word()
    {
        $this->pluralize('money')->shouldReturn('money');
        $this->pluralize('fat-sheep')->shouldReturn('fat-sheep');
        $this->pluralize('blowFish')->shouldReturn('blowFish');
    }

    public function it_is_irregular_word()
    {
        $this->pluralize('uglyMan')->shouldReturn('uglyMen');
        $this->pluralize('cow')->shouldReturn('kine');
        $this->pluralize('tricky-zombie')->shouldReturn('tricky-zombies');
    }

    public function it_is_plurals_word()
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
