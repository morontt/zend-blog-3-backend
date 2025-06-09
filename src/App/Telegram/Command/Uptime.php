<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 11.10.17
 * Time: 23:32
 */

namespace App\Telegram\Command;

use Symfony\Component\Process\Process;
use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Command\TelegramCommandTrait;
use Xelbot\Telegram\Entity\Message;

class Uptime implements TelegramCommandInterface
{
    use TelegramCommandTrait;

    /**
     * @param Message $message
     */
    public function execute(Message $message): void
    {
        $process = new Process(['uptime']);
        $process->run();

        // TODO Null pointer exception may occur here
        $this->requester->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => $process->getOutput(),
        ]);
    }
}
