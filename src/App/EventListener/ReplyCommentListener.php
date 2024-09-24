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
use Psr\Log\LoggerInterface;
use Xelbot\Telegram\Robot;

class ReplyCommentListener
{
    private Mailer $mailer;

    private LoggerInterface $logger;

    private Robot $bot;

    /**
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer, LoggerInterface $logger, Robot $bot)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->bot = $bot;
    }

    /**
     * @param CommentEvent $event
     */
    public function onReply(CommentEvent $event): void
    {
        try {
            $this->mailer->replyComment($event->getComment());
        } catch (\Throwable $e) {
            $this->logger->error('reply comment email error', ['exception' => $e]);
            $this->bot->sendMessage('reply comment email error: ' . $e->getMessage());
        }
    }
}
