<?php

namespace Mtt\BlogBundle\Event;

use Mtt\BlogBundle\Entity\PygmentsCode;
use Symfony\Component\EventDispatcher\Event;

class PygmentCodeEvent extends Event
{
    /**
     * @var PygmentsCode
     */
    private $snippet;

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
