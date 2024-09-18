<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 20.04.18
 * Time: 1:41
 */

namespace App\Utils;

class ExternalLinkProcessor
{
    /**
     * @var array
     */
    protected $hrefs = [];

    /**
     * @var array
     */
    protected $replaces = [];

    /**
     * @var array
     */
    protected $internalHosts;

    /**
     * @param array $hosts
     */
    public function __construct(array $hosts = [])
    {
        $this->internalHosts = $hosts;
    }

    /**
     * @param string|null $content
     *
     * @return string|null
     */
    public function upgradeLinks(string $content = null): ?string
    {
        if ($content === null) {
            return null;
        }

        $this->hrefs = [];
        $this->replaces = [];

        $oldHash = sha1($content);

        $content0 = $content;
        $fuse = 0;
        do {
            $r = $this->externalLinksProcessing($content0);
            $fuse++;
        } while ($r && $fuse < 200);

        if (count($this->hrefs)) {
            $content1 = $content;
            $fuse = 0;
            do {
                $r = $this->linksAndAttributeProcessing($content1);
                $fuse++;
            } while ($r && $fuse < 200);

            foreach ($this->replaces as $replacePair) {
                $content = str_replace($replacePair['old'], $replacePair['new'], $content);
            }

            return ($oldHash === sha1($content)) ? null : $content;
        }

        return null;
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    protected function externalLinksProcessing(&$text): bool
    {
        $result = false;
        $matches = [];

        $pattern = '/href="(?P<url>https?:\/\/(?P<host>[^\/]+)(\/?[^"]*))"/';

        if (preg_match($pattern, $text, $matches)) {
            $result = true;

            if (!in_array($matches['host'], $this->internalHosts, true)) {
                $this->hrefs[] = ['url' => $matches['url'], 'host' => $matches['host']];
            }

            $text = str_replace('href="' . $matches['url'] . '"', '', $text);
        }

        return $result;
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    protected function linksAndAttributeProcessing(&$text): bool
    {
        $result = false;
        $matches = [];

        $pattern = '/<a(?:\s+(?:[^>]+))>/';
        if (preg_match($pattern, $text, $matches)) {
            $result = true;

            $this->checkAttributes($matches[0]);

            $text = str_replace($matches[0], '', $text);
        }

        return $result;
    }

    /**
     * @param string $link
     */
    protected function checkAttributes(string $link): void
    {
        foreach ($this->hrefs as $externalLink) {
            if (strpos($link, $externalLink['url']) !== false) {
                $attributes = [];

                $linkItem = str_replace('>', '/>', $link);
                try {
                    $xml = simplexml_load_string($linkItem);
                    foreach ($xml->attributes() as $k => $v) {
                        $attributes[] = sprintf('%s="%s"', $k, (string)$v);
                    }
                } catch (\ErrorException $e) {
                    break;
                }

                $findRel = false;
                $newAttributes = [];
                foreach ($attributes as $attribute) {
                    $matches = [];
                    if (preg_match('/rel="([^"]+)"/', $attribute, $matches)) {
                        $findRel = true;
                        if (strpos($matches[1], 'nofollow') === false) {
                            $newAttributes[] = sprintf('rel="%s nofollow"', $matches[1]);
                        } else {
                            $newAttributes[] = $attribute;
                        }
                    } else {
                        $newAttributes[] = $attribute;
                    }
                }
                if (!$findRel) {
                    $newAttributes[] = 'rel="nofollow"';
                }

                $this->replaces[] = [
                    'old' => $link,
                    'new' => sprintf('<a %s>', implode(' ', $newAttributes)),
                ];
                break;
            }
        }
    }
}
