<?php

namespace App\Cron\Hourly;

use App\Cron\HourlyCronServiceInterface;
use App\Repository\TrackingRepository;
use App\Service\IpInfo;
use Throwable;

class TrackingGeoLocation implements HourlyCronServiceInterface
{
    /**
     * @var TrackingRepository
     */
    private TrackingRepository $repository;

    /**
     * @var IpInfo
     */
    private IpInfo $ipInfo;

    /**
     * @param IpInfo $ipInfo
     * @param TrackingRepository $repository
     */
    public function __construct(IpInfo $ipInfo, TrackingRepository $repository)
    {
        $this->repository = $repository;
        $this->ipInfo = $ipInfo;
    }

    public function run(): void
    {
        $ips = $this->repository->getUncheckedIps();
        $first = true;
        foreach ($ips as $ip) {
            if (!$first && $this->ipInfo->isLimitedRequests()) {
                sleep(1);
            }
            $first = false;
            try {
                $location = $this->ipInfo->getLocationByIp($ip);
                if ($location) {
                    $this->repository->updateLocation($location, $ip);
                }
            } catch (Throwable $e) {
                break;
            }
        }
    }

    public function getMessage(): ?string
    {
        return null;
    }
}
