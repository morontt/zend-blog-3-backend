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
     * @param string|null $countryCode
     *
     * @return string
     */
    public static function get(string $countryCode = null): string
    {
        if ($countryCode === '-') {
            // Pirate Flag Emoji
            return hex2bin('F09F8FB4E2808DE298A0EFB88F');
        }

        if (is_null($countryCode) || strlen($countryCode) !== 2) {
            throw new InvalidArgumentException('Please provide a 2 character country code.');
        }

        $countryCode = strtoupper($countryCode);

        return implode(
            '',
            array_map(
                [__CLASS__, 'convertChar'],
                str_split($countryCode)
            )
        );
    }

    private static function convertChar(string $char): string
    {
        $codepoint = self::INDICATOR_OFFSET + ord($char);

        $byte0 = 0b10000000 + ($codepoint & 0b111111);
        $byte1 = 0b10000000 + (($codepoint >> 6) & 0b111111);
        $byte2 = 0b10000000 + (($codepoint >> 12) & 0b111111);
        $byte3 = 0b11110000 + (($codepoint >> 18) & 0b111);

        return chr($byte3) . chr($byte2) . chr($byte1) . chr($byte0);
    }
}
