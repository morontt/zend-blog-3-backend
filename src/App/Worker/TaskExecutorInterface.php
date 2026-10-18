<?php

/**
 * User: morontt
 * Date: 18.10.2026
 * Time: 13:30
 */

namespace App\Worker;

use GearmanJob;

interface TaskExecutorInterface
{
    public function execute(GearmanJob $job): void;

    public function getTaskName(): string;

    public function getTimeout(): int;
}
