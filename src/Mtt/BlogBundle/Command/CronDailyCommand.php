<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:41
 */

namespace Mtt\BlogBundle\Command;

use Mtt\BlogBundle\Cron\CronServiceInterface;

class CronDailyCommand extends CronCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:cron:daily')
            ->setDescription('Start daily crons');
    }

    /**
     * @return CronServiceInterface[]
     */
    protected function getCrons(): array
    {
        return $this->chain->getDailyCrons();
    }
}
