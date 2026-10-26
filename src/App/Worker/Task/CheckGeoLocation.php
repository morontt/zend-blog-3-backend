<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 26.10.2026
 * Time: 08:59
 */

namespace App\Worker\Task;

use App\Repository\TrackingRepository;
use App\Service\IpInfo;
use App\Worker\BaseExecutor;
use Psr\Log\LoggerInterface;

class CheckGeoLocation extends BaseExecutor
{
    public function __construct(
        private IpInfo $ipInfo,
        private TrackingRepository $repository,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    public function getTaskName(): string
    {
        return 'check_geolocation';
    }

    /**
     * @param array<string, mixed> $data
     */
    public function handle(array $data): void
    {
        if (empty($data['ip']) || !filter_var($data['ip'], FILTER_VALIDATE_IP)) {
            return;
        }

        $ip = $data['ip'];
        $location = $this->ipInfo->getLocationByIp($ip);
        if ($location) {
            $this->repository->updateLocation($location, $ip);
        }
    }
}
