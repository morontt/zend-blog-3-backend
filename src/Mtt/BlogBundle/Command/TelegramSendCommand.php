<?php

namespace Mtt\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TelegramSendCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:telegram:send')
            ->setDescription('Test telegram bot')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bot = $this->getContainer()->get('mtt_blog.telegram_bot');
        $bot->getUpdates();
    }
}
