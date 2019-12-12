<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 25.09.17
 * Time: 22:16
 */

namespace Mtt\BlogBundle\Command\Telegram;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebhookInfoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:telegram:webhook-info')
            ->setDescription('Webhook info for telegram bot')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bot = $this->getContainer()->get('mtt_blog.telegram_bot');

        $result = $bot->getWebhookInfo();
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
    }
}
