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
    private $dailyCrons;

    /**
     * @var CronServiceInterface[]
     */
    private $hourlyCrons;

    public function __construct()
    {
        $this->hourlyCrons = [];
        $this->dailyCrons = [];
    }

    /**
     * @param CronServiceInterface $service
     */
    public function addCronDailyService(CronServiceInterface $service)
    {
        $this->dailyCrons[] = $service;
    }

    /**
     * @param CronServiceInterface $service
     */
    public function addCronHourlyService(CronServiceInterface $service)
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
