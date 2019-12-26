<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 12:06
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Cron\CronServiceInterface;
use Mtt\BlogBundle\Service\IpInfo;

class CommentGeoLocation implements CronServiceInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var IpInfo
     */
    protected $ipInfo;

    /**
     * @var int
     */
    protected $countImported = 0;

    /**
     * @param EntityManagerInterface $em
     * @param IpInfo $ipInfo
     */
    public function __construct(EntityManagerInterface $em, IpInfo $ipInfo)
    {
        $this->em = $em;
        $this->ipInfo = $ipInfo;
    }

    public function run()
    {
        $commentRepo = $this->em->getRepository('MttBlogBundle:Comment');

        $ips = $commentRepo->getUncheckedIps();
        foreach ($ips as $ip) {
            $location = $this->ipInfo->getLocationByIp($ip);
            if ($location) {
                $commentRepo->updateLocation($location, $ip);
                $this->countImported++;
            }

            sleep(2);
        }
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        $message = 'Nothing';
        if ($this->countImported == 1) {
            $message = '1 new location';
        } elseif ($this->countImported > 1) {
            $message = $this->countImported . ' new locations';
        }

        return $message;
    }
}
