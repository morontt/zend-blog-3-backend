<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 01.09.17
 * Time: 0:54
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Cron\CronServiceInterface;

class TrackingArchive implements CronServiceInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function run()
    {
        $this->em->getConnection()->query('CALL tracking_to_archive()');
    }
}
