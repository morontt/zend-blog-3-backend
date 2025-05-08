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
        return preg_replace_callback(
            '/<lj user="(?<name>[^"]+)"\/?>/m',
            static function (array $matches) {
                return sprintf(
                    '<a href="https://%s.livejournal.com/" class="lj-user">%s</a>',
                    $matches['name'],
                    $matches['name']
                );
            },
            $text
        );
    }
}
