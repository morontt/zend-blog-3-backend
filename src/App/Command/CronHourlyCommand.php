<?php

declare(strict_types=1);

namespace App\Command;

use App\Cron\CronServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'mtt:cron:hourly',
    description: 'Start hourly crons',
)]
class CronHourlyCommand extends CronCommand
{
    /**
     * @return CronServiceInterface[]
     */
    protected function getCrons(): array
    {
        return $this->chain->getHourlyCrons();
    }
}
