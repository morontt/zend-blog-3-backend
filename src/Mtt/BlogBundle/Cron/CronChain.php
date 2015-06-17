<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:16
 */

namespace Mtt\BlogBundle\Cron;

class CronChain
{
    /**
     * @var array
     */
    protected $dailyCrons;

    public function __construct()
    {
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
     * @return array
     */
    public function getDailyCrons()
    {
        return $this->dailyCrons;
    }
}
