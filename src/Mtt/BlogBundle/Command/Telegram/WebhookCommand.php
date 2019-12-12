<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 16.09.17
 * Time: 10:42
 */

namespace Mtt\BlogBundle\Command\Telegram;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebhookCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:telegram:webhook')
            ->setDescription('Set webhook for telegram bot')
            ->addArgument('url', InputArgument::REQUIRED, 'Webhook URL')
            ->addArgument('certificate', InputArgument::OPTIONAL, 'Path to certificate')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bot = $this->getContainer()->get('mtt_blog.telegram_bot');

        $result = $bot->setWebhook($input->getArgument('url'), $input->getArgument('certificate'));
        if ($result->isOk()) {
            $output->writeln($result->getDescription());
        }
    }
}
