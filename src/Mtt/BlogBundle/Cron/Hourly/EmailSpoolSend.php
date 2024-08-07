<?php

namespace Mtt\BlogBundle\Cron\Hourly;

use Mtt\BlogBundle\Cron\HourlyCronServiceInterface;
use Mtt\BlogBundle\Service\Mailer;

class EmailSpoolSend implements HourlyCronServiceInterface
{
    private Mailer $mailer;

    private int $emailsSent = 0;

    /**
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function run()
    {
        $this->emailsSent = $this->mailer->spoolSend(null, 60);
    }

    public function getMessage(): ?string
    {
        if ($this->emailsSent > 0) {
            return 'Отправлено писем: ' . $this->emailsSent;
        }

        return null;
    }
}
