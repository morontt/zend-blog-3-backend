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

        $want = "спасибо <a href=\"https://iridos-indium.livejournal.com/\" class=\"lj-user\">iridos_indium</a>\n";
        $want .= "снимок выпросил у него\n<a href=\"https://vasia.livejournal.com/\" class=\"lj-user\">vasia</a> ";

        $this::replaceUserTag($text)->shouldReturn($want);
    }

    public function it_is_lj_community_replace()
    {
        $text = 'в сообществе <lj comm="ru_radio_electr"> на';

        $want = 'в сообществе <a href="https://ru-radio-electr.livejournal.com/"';
        $want .= ' class="lj-comm">ru_radio_electr</a> на';

        $this::replaceUserTag($text)->shouldReturn($want);
    }

    public function it_is_clear_lj_cut()
    {
        $text = "<p><lj-cut text=\"Не для слабонервных...\">Аналитически задача\n</lj-cut><p>";
        $want = "<p>Аналитически задача\n<p>";

        $this::clearLjCutTag($text)->shouldReturn($want);
    }

    public function it_is_clear_lj_cut_part2()
    {
        $text = '<p><lj-cut text="Больше всего порадовала...">Больше всего порадовала <a target="_blank" href="http://example.org/IMG.JPG">коробка</a></p>';
        $want = '<p>Больше всего порадовала <a target="_blank" href="http://example.org/IMG.JPG">коробка</a></p>';

        $this::clearLjCutTag($text)->shouldReturn($want);
    }
}
