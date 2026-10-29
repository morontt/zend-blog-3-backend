<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 28.10.2026
 * Time: 07:50
 */

namespace App\Service;

use App\LogTrait;
use GearmanClient;
use Psr\Log\LoggerInterface;

class QueueClient
{
    use LogTrait;

    private GearmanClient $client;

    public function __construct(
        string $host,
        string $port,
        LoggerInterface $logger,
    ) {
        $this->client = new GearmanClient();
        $this->client->addServer($host, (int)$port);
        $this->client->setTimeout(1000);

        $this->setLogger($logger);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function put(string $taskName, array $data): void
    {
        $body = json_encode($data);
        $this->info('enqueued', [
            'task' => $taskName,
            'data' => $body,
        ]);

        $this->client->doBackground($taskName, $body);
        $code = $this->client->returnCode();
        if ($code !== GEARMAN_SUCCESS) {
            $this->critical('Gearman error', [
                'code' => $code,
                'task' => $taskName,
            ]);
        }
    }
}
