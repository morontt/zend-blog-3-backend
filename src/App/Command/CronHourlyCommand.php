<?php

declare(strict_types=1);

namespace App\Command;

use App\Cron\CronServiceInterface;

class CronHourlyCommand extends CronCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('mtt:cron:hourly')
            ->setDescription('Start hourly crons')
        ;
    }

    /**
     * @return CronServiceInterface[]
     */
    protected function getCrons(): array
    {
        return $this->chain->getHourlyCrons();
    }
}
