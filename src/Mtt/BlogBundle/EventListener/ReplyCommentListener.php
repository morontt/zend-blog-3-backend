<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 21:11
 */

namespace Mtt\BlogBundle\EventListener;

use Mtt\BlogBundle\Event\CommentEvent;
use Mtt\BlogBundle\Service\Mailer;

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
    public function onReply(CommentEvent $event)
    {
        $this->mailer->replyComment($event->getComment());
    }
}
