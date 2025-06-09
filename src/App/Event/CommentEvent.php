<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 21:20
 */

namespace App\Event;

use App\Entity\Comment;
use Symfony\Contracts\EventDispatcher\Event;

class CommentEvent extends Event
{
    private Comment $comment;

    /**
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return Comment
     */
    public function getComment(): Comment
    {
        return $this->comment;
    }
}
