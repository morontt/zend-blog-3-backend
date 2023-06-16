<?php

namespace Mtt\BlogBundle\Command;

use Mtt\BlogBundle\Cron\CronServiceInterface;

class CronHourlyCommand extends CronCommand
{
    protected function configure()
    {
        $this
            ->setName('mtt:cron:hourly')
            ->setDescription('Start hourly crons');
    }

    /**
     * @return CronServiceInterface[]
     */
    protected function getCrons(): array
    {
        return $this->chain->getHourlyCrons();
    }
}
