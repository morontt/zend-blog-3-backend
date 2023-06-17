<?php

namespace Mtt\BlogBundle\Cron\Hourly;

use Mtt\BlogBundle\Cron\HourlyCronServiceInterface;
use Mtt\BlogBundle\Entity\Repository\TrackingRepository;
use Mtt\BlogBundle\Service\IpInfo;

class TrackingGeoLocation implements HourlyCronServiceInterface
{
    /**
     * @var TrackingRepository
     */
    private $repository;

    /**
     * @var IpInfo
     */
    private $ipInfo;

    /**
     * @param IpInfo $ipInfo
     * @param TrackingRepository $repository
     */
    public function __construct(IpInfo $ipInfo, TrackingRepository $repository)
    {
        $this->repository = $repository;
        $this->ipInfo = $ipInfo;
    }

    public function run()
    {
        $ips = $this->repository->getUncheckedIps();
        foreach ($ips as $ip) {
            $location = $this->ipInfo->getLocationByIp($ip);
            if ($location) {
                $this->repository->updateLocation($location, $ip);
            }

            sleep(2);
        }
    }

    public function getMessage(): ?string
    {
        return null;
    }
}
