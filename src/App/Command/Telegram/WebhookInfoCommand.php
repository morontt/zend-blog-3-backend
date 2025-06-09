<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 25.09.17
 * Time: 22:16
 */

namespace App\Command\Telegram;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebhookInfoCommand extends AbstractTelegramCommand
{
    protected function configure(): void
    {
        $this
            ->setName('mtt:telegram:webhook-info')
            ->setDescription('Webhook info for telegram bot')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->bot->getWebhookInfo();
        if ($result->isOk()) {
            $rows = [];

            foreach ($result->getResult() as $key => $value) {
                $rows[] = [$key, $value];
            }

            $table = new Table($output);
            $table
                ->setHeaders(['property', 'value'])
                ->setRows($rows)
            ;
            $table->render();
        }

        return 0;
    }
}
