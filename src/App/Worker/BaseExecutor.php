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
use Throwable;

abstract class BaseExecutor implements TaskExecutorInterface
{
    use LogTrait;

    public function __construct(
        LoggerInterface $logger,
    ) {
        $this->setLogger($logger);
    }

    /**
     * @param array<string, mixed> $data
     */
    abstract public function handle(array $data): void;

    abstract public function getTaskName(): string;

    public function getTimeout(): int
    {
        return 10;
    }

    public function execute(GearmanJob $job): void
    {
        $data = $this->workload($job);
        if (!$data) {
            $job->sendFail();

            return;
        }

        $this->debug('job data', $data);
        try {
            $this->handle($data);
        } catch (Throwable $e) {
            $this->critical('an error occurred', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $job->sendException($e->getMessage());

            return;
        }

        $job->sendComplete('');
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function workload(GearmanJob $job): ?array
    {
        $workload = $job->workload();
        try {
            return json_decode($workload, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->error('decode error', [
                'error' => $e->getMessage(),
                'workload' => $workload,
            ]);

            return null;
        }
    }
}
