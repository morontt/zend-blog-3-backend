<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.09.17
 * Time: 0:14
 */

namespace App\Command\Telegram;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebhookDeleteCommand extends AbstractTelegramCommand
{
    protected function configure(): void
    {
        $this
            ->setName('mtt:telegram:webhook-delete')
            ->setDescription('Delete Webhook for telegram bot')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->bot->deleteWebhook();
        if ($result->isOk()) {
            $output->writeln($result->getDescription());
        }

        return 0;
    }
}
