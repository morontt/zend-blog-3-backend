<?php
/**
 * User: morontt
 * Date: 02.12.2024
 * Time: 20:34
 */

namespace App\Service\Metrics;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use PrometheusPushGateway\PushGateway;

class PrometheusPushGateway
{
    private const NAMESPACE = 'reprogl';

    private PushGateway $pushGateway;
    private CollectorRegistry $registry;

    public function __construct(string $dsn)
    {
        $this->pushGateway = new PushGateway($dsn);
        $this->registry = new CollectorRegistry(new InMemory());
    }

    public function pushCertificateExpiration(int $value): void
    {
        $gauge = $this->registry->getOrRegisterGauge(
            self::NAMESPACE,
            'certificate_expiration',
            'How many days left until the SSL-certificate expires'
        );

        $gauge->set($value);
        $this->push();
    }

    private function push()
    {
        $this->pushGateway->push($this->registry, 'cron_daily', ['instance' => 'zendblog_backend']);
    }
}
