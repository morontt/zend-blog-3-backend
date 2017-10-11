<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 11.10.17
 * Time: 0:10
 */

namespace Xelbot\Telegram\Command;

use Xelbot\Telegram\Entity\Message;
use Xelbot\Telegram\TelegramRequester;

interface TelegramCommandInterface
{
    /**
     * @param Message $message
     */
    public function execute(Message $message): void;

    /**
     * @return string
     */
    public function getCommandName(): string;

    /**
     * @param TelegramRequester $requester
     */
    public function setRequester(TelegramRequester $requester): void;
}
