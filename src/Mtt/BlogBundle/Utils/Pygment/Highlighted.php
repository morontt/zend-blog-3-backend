<?php

namespace Mtt\BlogBundle\Utils\Pygment;

class Highlighted
{
    /**
     * @var array
     */
    private $strings;

    public function __construct(array $strings)
    {
        $this->strings = $strings;
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
        return implode("\n", array_slice($this->strings, 0, 8));
    }
}
