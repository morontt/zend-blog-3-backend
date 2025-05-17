<?php
/**
 * User: morontt
 * Date: 08.05.2025
 * Time: 21:14
 */

namespace App\Utils;

class LiveJournalHelper
{
    public static function replaceUserTag(string $text): string
    {
        $text = preg_replace_callback(
            '/<lj user="(?<name>[^"]+)"\/?>/m',
            static function (array $matches) {
                return sprintf(
                    '<a href="https://%s.livejournal.com/" class="lj-user">%s</a>',
                    str_replace('_', '-', $matches['name']),
                    $matches['name']
                );
            },
            $text
        );

        return preg_replace_callback(
            '/<lj comm="(?<name>[^"]+)"\/?>/m',
            static function (array $matches) {
                return sprintf(
                    '<a href="https://%s.livejournal.com/" class="lj-comm">%s</a>',
                    str_replace('_', '-', $matches['name']),
                    $matches['name']
                );
            },
            $text
        );
    }

    public static function clearLjCutTag(string $text): string
    {
        return preg_replace('/<\/?lj-cut(?:\s+text="[^"]+")?>/m', '', $text);
    }
}
