<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 01.09.17
 * Time: 0:54
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Cron\CronServiceInterface;

class TrackingArchive implements CronServiceInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $message = 'test';

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws DBALException
     */
    public function run()
    {
        // $this->em->getConnection()->executeQuery('CALL tracking_to_archive()');
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Temporary disabled';
    }
}
