<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 29.10.2026
 * Time: 06:27
 */

namespace App\Worker\Task;

use App\Service\Mailer;
use App\Worker\BaseExecutor;
use Psr\Log\LoggerInterface;

class SpoolSend extends BaseExecutor
{
    public const TASK_NAME = 'spool_send';

    public function __construct(
        private Mailer $mailer,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    public function getTaskName(): string
    {
        return self::TASK_NAME;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function handle(array $data): void
    {
        if (empty($data['file'])) {
            return;
        }

        $file = $data['file'];
        if ($this->mailer->spoolSendFile($file)) {
            $this->info('Email successfully sent', ['file' => $file]);
        } else {
            $this->error('Email not sent', ['file' => $file]);
        }
    }
}
