<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 28.02.16
 * Time: 18:42
 */

namespace App\Utils;

/**
 * PHP implementation Ember.Inflector.pluralize
 *
 * @see https://github.com/stefanpenner/ember-inflector/blob/1.9.4/addon/lib/system/inflections.js
 * @see https://github.com/stefanpenner/ember-inflector/blob/1.9.4/addon/lib/system/inflector.js
 *
 * Class Inflector
 */
class Inflector
{
    private const BLANK_REGEX = '/^\s*$/';
    private const LAST_WORD_DASHED_REGEX = '/([\w\/-]+[_\/\s-])([a-z\d]+$)/';
    private const LAST_WORD_CAMELIZED_REGEX = '/([\w\/\s-]+)([A-Z][a-z\d]*$)/';
    private const CAMELIZED_REGEX = '/[A-Z][a-z\d]*$/';

    protected static array $plurals = [
        ['/$/', 's'],
        ['/s$/i', 's'],
        ['/^(ax|test)is$/i', '$1es'],
        ['/(octop|vir)us$/i', '$1i'],
        ['/(octop|vir)i$/i', '$1i'],
        ['/(alias|status)$/i', '$1es'],
        ['/(bu)s$/i', '$1ses'],
        ['/(buffal|tomat)o$/i', '$1oes'],
        ['/([ti])um$/i', '$1a'],
        ['/([ti])a$/i', '$1a'],
        ['/sis$/i', 'ses'],
        ['/(?:([^f])fe|([lr])f)$/i', '$1$2ves'],
        ['/(hive)$/i', '$1s'],
        ['/([^aeiouy]|qu)y$/i', '$1ies'],
        ['/(x|ch|ss|sh)$/i', '$1es'],
        ['/(matr|vert|ind)(?:ix|ex)$/i', '$1ices'],
        ['/^(m|l)ouse$/i', '$1ice'],
        ['/^(m|l)ice$/i', '$1ice'],
        ['/^(ox)$/i', '$1en'],
        ['/^(oxen)$/i', '$1'],
        ['/(quiz)$/i', '$1zes'],
    ];

    protected static array $irregularPairs = [
        ['person', 'people'],
        ['man', 'men'],
        ['child', 'children'],
        ['sex', 'sexes'],
        ['move', 'moves'],
        ['cow', 'kine'],
        ['zombie', 'zombies'],
    ];

    protected static array $uncountable = [
        'equipment',
        'information',
        'rice',
        'money',
        'species',
        'series',
        'fish',
        'sheep',
        'jeans',
        'police',
    ];

    /**
     * @param string $word
     *
     * @return string
     */
    public static function pluralize(string $word): string
    {
        if (!$word || preg_match(self::BLANK_REGEX, $word)) {
            return $word;
        }

        $isCamelized = preg_match(self::CAMELIZED_REGEX, $word);
        $firstPhrase = '';
        $lastWord = $word;

        $lowercase = strtolower($word);
        $wordSplit = [];
        if (preg_match(self::LAST_WORD_DASHED_REGEX, $word, $wordSplit)) {
            $firstPhrase = $wordSplit[1];
            $lastWord = strtolower($wordSplit[2]);
        } else {
            $wordSplit = [];
            if (preg_match(self::LAST_WORD_CAMELIZED_REGEX, $word, $wordSplit)) {
                $firstPhrase = $wordSplit[1];
                $lastWord = strtolower($wordSplit[2]);
            }
        }

        if (in_array($lowercase, self::$uncountable) || in_array($lastWord, self::$uncountable)) {
            return $word;
        }

        foreach (self::$irregularPairs as $rule) {
            if ($rule[0] === $lastWord) {
                $substitution = $rule[1];
                if ($isCamelized) {
                    $substitution = ucfirst($substitution);
                }

                return $firstPhrase . $substitution;
            }
        }

        for ($i = count(self::$plurals); $i > 0; $i--) {
            $inflection = self::$plurals[$i - 1];
            if (preg_match($inflection[0], $lastWord)) {
                $res = preg_replace($inflection[0], $inflection[1], $lastWord);
                if ($isCamelized) {
                    $res = ucfirst($res);
                }

                return $firstPhrase . $res;
            }
        }

        return 'n/a';
    }
}
