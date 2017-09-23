<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 24.09.17
 * Time: 1:57
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Symfony\Component\Process\Process;

class UptimeCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'uptime';

    /**
     * @var string
     */
    protected $description = 'Server uptime';

    /**
     * @var string
     */
    protected $usage = '/uptime';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    public function execute()
    {
        $process = new Process('uptime');
        $process->run();

        return Request::sendMessage([
            'chat_id' => $this->getMessage()->getChat()->getId(),
            'text' => $process->getOutput(),
        ]);
    }
}
