<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 01.09.17
 * Time: 0:54
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\DBAL\Exception as DBALException;
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
     * @var int|string
     */
    private $rows;

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
        $stmtResult = $this->em->getConnection()->executeQuery('CALL tracking_to_archive()');
        $this->rows = $stmtResult->rowCount();
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Complete. ' . $this->rows . ' rows affected';
    }
}
