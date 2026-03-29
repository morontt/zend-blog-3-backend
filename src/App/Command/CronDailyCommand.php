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
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'mtt:cron:daily',
    description: 'Start daily crons',
)]
class CronDailyCommand extends CronCommand
{
    /**
     * @return CronServiceInterface[]
     */
    protected function getCrons(): array
    {
        return $this->chain->getDailyCrons();
    }
}
