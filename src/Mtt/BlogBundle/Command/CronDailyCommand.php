<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:41
 */

namespace Mtt\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronDailyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:cron:daily')
            ->setDescription('Start daily crons');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $cronChain = $this->getContainer()->get('mtt_blog.cron_chain');
        foreach ($cronChain->getDailyCrons() as $cronJob) {
            $cronJob->run();
        }
    }
}
