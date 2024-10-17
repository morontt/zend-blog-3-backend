<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:18
 */

namespace App\Cron;

interface CronServiceInterface
{
    public function run(): void;

    /**
     * @return string|null
     */
    public function getMessage(): ?string;
}
