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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class CommentGeoLocation implements DailyCronServiceInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var IpInfo
     */
    protected IpInfo $ipInfo;

    /**
     * @var int
     */
    protected int $countImported = 0;

    /**
     * @var SystemParametersStorage
     */
    private SystemParametersStorage $paramStorage;

    /**
     * @param SystemParametersStorage $paramStorage
     * @param EntityManagerInterface $em
     * @param IpInfo $ipInfo
     */
    public function __construct(EntityManagerInterface $em, IpInfo $ipInfo, SystemParametersStorage $paramStorage)
    {
        $this->em = $em;
        $this->ipInfo = $ipInfo;
        $this->paramStorage = $paramStorage;
    }

    public function run(): void
    {
        $commentRepo = $this->em->getRepository(Comment::class);

        $ips = $commentRepo->getUncheckedIps();
        $first = true;
        foreach ($ips as $ip) {
            if (!$first) {
                sleep(1);
            }
            $first = false;
            $location = $this->ipInfo->getLocationByIp($ip);
            if ($location) {
                $commentRepo->updateLocation($location, $ip);
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
