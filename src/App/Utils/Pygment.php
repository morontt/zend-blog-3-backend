<?php

namespace App\Utils;

use App\Utils\Pygment\Highlighted;

class Pygment
{
    /**
     * @param string $content
     * @param string $lexer
     *
     * @return Highlighted
     */
    public static function highlight(string $content, string $lexer): Highlighted
    {
        $file = sys_get_temp_dir() . '/pygments_' . time();
        file_put_contents($file, $content);

        $outputTable = [];
        $outputInline = [];
        exec('pygmentize -f html -l ' . $lexer . ' -O linenos=table ' . escapeshellarg($file), $outputTable);
        exec('pygmentize -f html -l ' . $lexer . ' ' . escapeshellarg($file), $outputInline);
        unlink($file);

        return new Highlighted($outputTable, $outputInline);
    }
}
