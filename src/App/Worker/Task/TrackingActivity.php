<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 18.10.2026
 * Time: 14:00
 */

namespace App\Worker\Task;

use App\Worker\BaseExecutor;
use GearmanJob;
use Psr\Log\LoggerInterface;

class TrackingActivity extends BaseExecutor
{
    public function __construct(
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    public function execute(GearmanJob $job): void
    {
        $data = $this->workload($job);
        if (!$data) {
            $job->sendFail();

            return;
        }

        $this->debug('tracking data', $data);

        $job->sendComplete('');
    }

    public function getTaskName(): string
    {
        return 'tracking_activity';
    }
}
