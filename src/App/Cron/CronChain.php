<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:16
 */

namespace App\Cron;

class CronChain
{
    /**
     * @var CronServiceInterface[]
     */
    private $dailyCrons = [];

    /**
     * @var CronServiceInterface[]
     */
    private $hourlyCrons = [];

    public function addCronDailyService(CronServiceInterface $service): void
    {
        $this->dailyCrons[] = $service;
    }

    public function addCronHourlyService(CronServiceInterface $service): void
    {
        $this->hourlyCrons[] = $service;
    }

    /**
     * @return CronServiceInterface[]
     */
    public function getDailyCrons(): array
    {
        return $this->dailyCrons;
    }

    /**
     * @return CronServiceInterface[]
     */
    public function getHourlyCrons(): array
    {
        return $this->hourlyCrons;
    }
}
