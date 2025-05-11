<?php
/**
 * User: morontt
 * Date: 11.05.2025
 * Time: 20:28
 */

namespace App\Event;

use App\Entity\Commentator;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateCommentatorEvent extends Event
{
    private Commentator $commentator;

    public function __construct(Commentator $commentator)
    {
        $this->commentator = $commentator;
    }

    public function getCommentator(): Commentator
    {
        return $this->commentator;
    }
}
