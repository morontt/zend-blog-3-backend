<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 12:06
 */

namespace App\Cron\Daily;

use App\Cron\DailyCronServiceInterface;
use App\Doctrine\DBAL\Type\MillisecondsDateTime;
use App\Entity\Comment;
use App\Entity\GeoLocation;
use App\Entity\SystemParameters;
use App\Service\IpInfo;
use App\Service\SystemParametersStorage;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class CommentGeoLocation implements DailyCronServiceInterface
{
    /**
     * @var int
     */
    protected int $countImported = 0;

    public function __construct(
        private EntityManagerInterface $em,
        private IpInfo $ipInfo,
        private SystemParametersStorage $paramStorage,
    ) {
    }

    public function run(): void
    {
        /** @var \App\Repository\CommentRepository */
        $commentRepo = $this->em->getRepository(Comment::class);

        $ips = $commentRepo->getUncheckedIps();
        $first = true;
        foreach ($ips as $ip) {
            if (!$first) {
                sleep(1);
            }
            $first = false;
            try {
                $location = $this->ipInfo->getLocationByIp($ip);
                if ($location) {
                    $commentRepo->updateLocation($location, $ip);
                }
            } catch (\Throwable $e) {
                break;
            }
        }

        $from = $this->paramStorage->getParameter(SystemParameters::UPDATE_GEOLOCATION_FROM)
            ?? (new DateTime())->sub(new DateInterval('P1D'))->format('Y-m-d H:i:s');
        $now = (new DateTime())->format(MillisecondsDateTime::FORMAT_TIME);

        $geolocationRepo = $this->em->getRepository(GeoLocation::class);
        $this->countImported = $geolocationRepo->getLocationsCount($from, $now);

        $this->paramStorage->saveParameter(SystemParameters::UPDATE_GEOLOCATION_FROM, $now);
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        $message = 'Nothing';
        if ($this->countImported === 1) {
            $message = '1 new location';
        } elseif ($this->countImported > 1) {
            $message = $this->countImported . ' new locations';
        }

        return $message;
    }
}
