<?php

namespace Mtt\BlogBundle\Utils;

use Mtt\BlogBundle\Utils\Pygment\Highlighted;

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

        $output = [];
        exec('pygmentize -f html -l ' . $lexer . ' -O linenos=inline ' . escapeshellarg($file), $output);
        unlink($file);

        return new Highlighted($output);
    }
}
