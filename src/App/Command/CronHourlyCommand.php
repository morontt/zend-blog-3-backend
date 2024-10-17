<?php

namespace App\Command;

use App\Cron\CronServiceInterface;

class CronHourlyCommand extends CronCommand
{
    protected function configure(): void
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
