<?php

namespace App\Event;

use App\Entity\PygmentsCode;
use Symfony\Contracts\EventDispatcher\Event;

class PygmentCodeEvent extends Event
{
    private PygmentsCode $snippet;

    /**
     * @param PygmentsCode $snippet
     */
    public function __construct(PygmentsCode $snippet)
    {
        $this->snippet = $snippet;
    }

    public function getPygmentsCode(): PygmentsCode
    {
        return $this->snippet;
    }
}
