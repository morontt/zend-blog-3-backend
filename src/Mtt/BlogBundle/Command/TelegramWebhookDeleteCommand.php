<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.09.17
 * Time: 0:14
 */

namespace Mtt\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TelegramWebhookDeleteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:telegram:webhook-delete')
            ->setDescription('Delete Webhook for telegram bot')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bot = $this->getContainer()->get('mtt_blog.telegram_bot');

        $result = $bot->deleteWebhook();
        if ($result->isOk()) {
            $output->writeln($result->getDescription());
        }
    }
}
