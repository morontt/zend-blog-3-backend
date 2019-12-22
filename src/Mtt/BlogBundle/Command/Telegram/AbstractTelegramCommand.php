<?php

namespace Mtt\BlogBundle\Command\Telegram;

use Symfony\Component\Console\Command\Command;
use Xelbot\Telegram\Robot;

abstract class AbstractTelegramCommand extends Command
{
    /**
     * @var Robot
     */
    protected $bot;

    /**
     * @param Robot $bot
     */
    public function __construct(Robot $bot)
    {
        parent::__construct();

        $this->bot = $bot;
    }
}
