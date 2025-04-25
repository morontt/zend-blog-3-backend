<?php

namespace App\Utils;

class RuTransform
{
    public static function ruTransform($value = null)
    {
        // replace non letter or digits by -
        $value = trim(preg_replace('/[^\pL\d]+/u', '-', $value), '-');

        $transform = [
            'А' => 'A',    'а' => 'a',
            'Б' => 'B',    'б' => 'b',
            'В' => 'V',    'в' => 'v',
            'Г' => 'G',    'г' => 'g',
            'Д' => 'D',    'д' => 'd',
            'Е' => 'E',    'е' => 'e',
            'Ё' => 'E',    'ё' => 'e',
            'Ж' => 'Zh',   'ж' => 'zh',
            'З' => 'Z',    'з' => 'z',
            'И' => 'I',    'и' => 'i',
            'Й' => 'Y',    'й' => 'y',
            'К' => 'K',    'к' => 'k',
            'Л' => 'L',    'л' => 'l',
            'М' => 'M',    'м' => 'm',
            'Н' => 'N',    'н' => 'n',
            'О' => 'O',    'о' => 'o',
            'П' => 'P',    'п' => 'p',
            'Р' => 'R',    'р' => 'r',
            'С' => 'S',    'с' => 's',
            'Т' => 'T',    'т' => 't',
            'У' => 'U',    'у' => 'u',
            'Ф' => 'F',    'ф' => 'f',
            'Х' => 'Kh',   'х' => 'kh',
            'Ц' => 'Ts',   'ц' => 'ts',
            'Ч' => 'Ch',   'ч' => 'ch',
            'Ш' => 'Sh',   'ш' => 'sh',
            'Щ' => 'Sc',   'щ' => 'sc',
            'Ъ' => '',     'ъ' => '',
            'Ы' => 'Y',    'ы' => 'y',
            'Ь' => '',     'ь' => '',
            'Э' => 'E',    'э' => 'e',
            'Ю' => 'Yu',   'ю' => 'yu',
            'Я' => 'Ya',   'я' => 'ya',
        ];
        $konform_temp = strtolower(strtr($value, $transform));

        $result = strtr($konform_temp, [
            '---' => '-',
            '--' => '-',
        ]);

        if (empty($result)) {
            $result = 'n-a';
        }

        return $result;
    }
}
