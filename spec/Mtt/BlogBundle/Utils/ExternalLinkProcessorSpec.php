<?php

namespace spec\Mtt\BlogBundle\Utils;

use Mtt\BlogBundle\Utils\ExternalLinkProcessor;
use PhpSpec\ObjectBehavior;

class ExternalLinkProcessorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(['morontt.info', 'cdn.morontt.info']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ExternalLinkProcessor::class);
    }

    public function it_is_internal_links()
    {
        $content = 'Test with <a href="https://morontt.info/about">Internal Link</a>';
        $this->upgradeLinks($content)->shouldBeNull();

        $content = <<<RAW
Test with <a href="https://cdn.morontt.info/info">Internal Link</a>
123
RAW;
        $this->upgradeLinks($content)->shouldBeNull();

        $content = 'Test with <a href="/statistika">Internal Link</a>';
        $this->upgradeLinks($content)->shouldBeNull();
    }

    public function it_is_content_without_links()
    {
        $this->upgradeLinks('Lorem ipsum')->shouldBeNull();
    }

    public function it_is_external_links()
    {
        $content = <<<RAW
Test with <a href="https://morontt.info/info">Internal Link</a>
And <a href="http://example.org/best-website-designs"  target="_blank">External Link</a>...
RAW;
        $pureContent = <<<RAW
Test with <a href="https://morontt.info/info">Internal Link</a>
And <a href="http://example.org/best-website-designs" target="_blank" rel="nofollow">External Link</a>...
RAW;
        $this->upgradeLinks($content)->shouldReturn($pureContent);
    }

    public function it_is_external_links_with_rel()
    {
        $content = <<<RAW
Test with <a href="https://morontt.info/about">Internal Link</a>
And <a href="http://example.org/one/two" rel="noopener" target="_blank">External Link</a>,
And <a href="http://example.org/" rel="nofollow noopener">External Link 2</a>...
RAW;
        $pureContent = <<<RAW
Test with <a href="https://morontt.info/about">Internal Link</a>
And <a href="http://example.org/one/two" rel="noopener nofollow" target="_blank">External Link</a>,
And <a href="http://example.org/" rel="nofollow noopener">External Link 2</a>...
RAW;
        $this->upgradeLinks($content)->shouldReturn($pureContent);
    }

    public function it_is_equals_before_after()
    {
        $content = 'Lorem <a href="https://validator.w3.org/feed/" target="_blank" rel="nofollow">Ipsum</a>';
        $this->upgradeLinks($content)->shouldBeNull();
    }

    public function it_is_null()
    {
        $this->upgradeLinks(null)->shouldBeNull();
    }
}
