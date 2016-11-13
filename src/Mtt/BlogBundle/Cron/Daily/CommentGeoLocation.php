<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 12:06
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\ORM\EntityManager;
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
     * @param EntityManager $em
     * @param IpInfo $ipInfo
     */
    public function __construct(EntityManager $em, IpInfo $ipInfo)
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
            }

            sleep(2);
        }
    }
}
