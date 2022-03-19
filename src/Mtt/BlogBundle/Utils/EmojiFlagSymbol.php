<?php

namespace Mtt\BlogBundle\Utils;

use InvalidArgumentException;

/**
 * Class EmojiFlagSymbol
 *
 * Examples:
 * https://emojipedia.org/flags/
 * https://apps.timwhitlock.info/unicode/inspect/hex/1F1F2/1F1E9
 */
class EmojiFlagSymbol
{
    /**
     * The number by which to offset the character code to get the regional indicator
     *
     * @var int
     */
    const INDICATOR_OFFSET = 127397;

    /**
     * @param string $countryCode
     *
     * @return string
     */
    public static function get($countryCode)
    {
        if (strlen($countryCode) !== 2) {
            throw new InvalidArgumentException('Please provide a 2 character country code.');
        }

        $countryCode = strtoupper($countryCode);

        return implode(
            '',
            array_map(
                function ($char) {
                    return mb_convert_encoding(
                        sprintf('&#%d;', ord($char) + self::INDICATOR_OFFSET),
                        'UTF-8',
                        'HTML-ENTITIES'
                    );
                },
                str_split($countryCode)
            )
        );
    }
}
