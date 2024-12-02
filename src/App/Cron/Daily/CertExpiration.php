<?php
/**
 * User: morontt
 * Date: 02.12.2024
 * Time: 17:54
 */

namespace App\Cron\Daily;

use App\Cron\DailyCronServiceInterface;
use App\Service\Metrics\PrometheusPushGateway;

class CertExpiration implements DailyCronServiceInterface
{
    private PrometheusPushGateway $gateway;
    private int $days = 0;

    public function __construct(PrometheusPushGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function run(): void
    {
        $this->days = mt_rand(0, 90);
        $this->gateway->pushCertificateExpiration($this->days);
    }

    public function getMessage(): ?string
    {
        return sprintf('Valid for %d days', $this->days);
    }
}
