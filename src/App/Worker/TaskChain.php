<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 18.10.2026
 * Time: 14:43
 */

namespace App\Worker;

class TaskChain
{
    /**
     * @var TaskExecutorInterface[]
     */
    private $executors = [];

    public function addTaskExecutor(TaskExecutorInterface $executor): void
    {
        $this->executors[] = $executor;
    }

    /**
     * @return TaskExecutorInterface[]
     */
    public function getTaskExecutors(): array
    {
        return $this->executors;
    }
}
