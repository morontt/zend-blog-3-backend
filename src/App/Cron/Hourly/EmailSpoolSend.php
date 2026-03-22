<?php

declare(strict_types=1);

namespace App\Cron\Hourly;

use App\Cron\HourlyCronServiceInterface;
use App\Service\Mailer;

class EmailSpoolSend implements HourlyCronServiceInterface
{
    private int $emailsSent = 0;

    public function __construct(private Mailer $mailer)
    {
    }

    public function run(): void
    {
        $this->emailsSent = $this->mailer->spoolSend(timeLimit: 60);
    }

    public function getMessage(): ?string
    {
        if ($this->emailsSent > 0) {
            return 'Отправлено писем: ' . $this->emailsSent;
        }

        return null;
    }
}
