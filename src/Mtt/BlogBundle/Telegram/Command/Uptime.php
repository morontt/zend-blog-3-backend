<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 11.10.17
 * Time: 23:32
 */

namespace Mtt\BlogBundle\Telegram\Command;

use Symfony\Component\Process\Process;
use Xelbot\Telegram\Command\RequesterTrait;
use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Entity\Message;

class Uptime implements TelegramCommandInterface
{
    use RequesterTrait;

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return 'uptime';
    }

    /**
     * @param Message $message
     */
    public function execute(Message $message): void
    {
        $process = new Process('uptime');
        $process->run();

        $this->requester->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => $process->getOutput(),
        ]);
    }
}
