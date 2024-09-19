<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 21:11
 */

namespace App\EventListener;

use App\Event\CommentEvent;
use App\Service\Mailer;

class ReplyCommentListener
{
    private Mailer $mailer;

    /**
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param CommentEvent $event
     */
    public function onReply(CommentEvent $event): void
    {
        $this->mailer->replyComment($event->getComment());
    }
}
