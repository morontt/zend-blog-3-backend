<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:41
 */

namespace App\Command;

use App\Cron\CronServiceInterface;

class CronDailyCommand extends CronCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('mtt:cron:daily')
            ->setDescription('Start daily crons')
        ;
    }

    /**
     * @return CronServiceInterface[]
     */
    protected function getCrons(): array
    {
        return $this->chain->getDailyCrons();
    }
}
