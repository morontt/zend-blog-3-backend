<?php

namespace App\Utils\Pygment;

class Highlighted
{
    /**
     * @var string[]
     */
    private $strings;

    /**
     * @var string[]
     */
    private $stringsInline;

    /**
     * @param string[] $stringsTable
     * @param string[] $stringsInline
     */
    public function __construct(array $stringsTable, array $stringsInline)
    {
        $this->strings = $stringsTable;
        $this->stringsInline = $stringsInline;
    }

    /**
     * @return string
     */
    public function html(): string
    {
        return implode("\n", $this->strings);
    }

    /**
     * @return string
     */
    public function htmlPreview(): string
    {
        return implode("\n", array_slice($this->stringsInline, 0, 8));
    }
}
