<?php

namespace spec\App\Utils;

use App\Utils\LiveJournalHelper;
use PhpSpec\ObjectBehavior;

class LiveJournalHelperSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(LiveJournalHelper::class);
    }

    public function it_is_ljuser_replace_empty()
    {
        $this::replaceUserTag('test text')->shouldReturn('test text');
    }

    public function it_is_ljuser_replace()
    {
        $text = "спасибо <lj user=\"iridos_indium\">\nснимок выпросил у него\n<lj user=\"vasia\"> ";
        $want = "спасибо <a href=\"https://iridos_indium.livejournal.com/\" class=\"lj-user\">iridos_indium</a>\n";
        $want .= "снимок выпросил у него\n<a href=\"https://vasia.livejournal.com/\" class=\"lj-user\">vasia</a> ";

        $this::replaceUserTag($text)->shouldReturn($want);
    }

    public function it_is_clear_lj_cut()
    {
        $text = "<p><lj-cut text=\"Не для слабонервных...\">Аналитически задача\n</lj-cut><p>";
        $want = "<p>Аналитически задача\n<p>";

        $this::clearLjCutTag($text)->shouldReturn($want);
    }
}
