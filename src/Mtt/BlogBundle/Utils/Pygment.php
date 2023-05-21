<?php

namespace Mtt\BlogBundle\Utils;

class Pygment
{
    /**
     * @param $content
     * @param $lexer
     *
     * @return string|null
     */
    public static function highlight($content, $lexer): ?string
    {
        $file = sys_get_temp_dir() . '/pygments_' . time();
        file_put_contents($file, $content);

        $output = [];
        exec('pygmentize -f html -l ' . $lexer . ' -O linenos=inline ' . escapeshellarg($file), $output);

        $result = implode("\n", $output);
        unlink($file);

        return $result;
    }
}
