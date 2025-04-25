<?php
/**
 * User: morontt
 * Date: 02.12.2024
 * Time: 17:54
 */

namespace App\Cron\Daily;

use App\Cron\DailyCronServiceInterface;
use App\Service\Metrics\PrometheusPushGateway;
use DateTime;

class CertExpiration implements DailyCronServiceInterface
{
    private PrometheusPushGateway $gateway;
    private int $days = 0;
    private string $frontendSite;

    public function __construct(PrometheusPushGateway $gateway, string $frontendSite)
    {
        $this->gateway = $gateway;
        $this->frontendSite = $frontendSite;
    }

    public function run(): void
    {
        $domain = parse_url($this->frontendSite, PHP_URL_HOST);

        $get = stream_context_create(['ssl' => ['capture_peer_cert' => true]]);
        $res = stream_socket_client("ssl://{$domain}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
        $cont = stream_context_get_params($res);
        $data = openssl_x509_parse($cont['options']['ssl']['peer_certificate']);

        if (!empty($data['validTo_time_t'])) {
            $diff = (int)$data['validTo_time_t'] - (int)(new DateTime())->format('U');

            $this->days = (int)($diff / 86400) - 1;
        }

        $this->gateway->pushCertificateExpiration($this->days);
    }

    public function getMessage(): ?string
    {
        return sprintf('Valid for %d days', $this->days);
    }
}
