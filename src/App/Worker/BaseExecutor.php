<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 18.10.2026
 * Time: 13:34
 */

namespace App\Worker;

use App\LogTrait;
use GearmanJob;
use JsonException;
use Psr\Log\LoggerInterface;

abstract class BaseExecutor implements TaskExecutorInterface
{
    use LogTrait;

    public function __construct(
        LoggerInterface $logger,
    ) {
        $this->setLogger($logger);
    }

    abstract public function execute(GearmanJob $job): void;

    abstract public function getTaskName(): string;

    public function getTimeout(): int
    {
        return 10;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function workload(GearmanJob $job): ?array
    {
        try {
            return json_decode($job->workload(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->error('decode error', [$e->getMessage()]);

            return null;
        }
    }
}
