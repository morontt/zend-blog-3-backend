<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 01.09.17
 * Time: 0:54
 */

namespace App\Cron\Daily;

use App\Cron\DailyCronServiceInterface;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class TrackingArchive implements DailyCronServiceInterface
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
    public function run(): void
    {
        $stmtResult = $this->em->getConnection()->executeQuery('CALL tracking_to_archive()');
        $this->rows = $stmtResult->rowCount();
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        if (!$this->rows) {
            return null;
        }

        return 'Complete. ' . $this->rows . ' rows affected';
    }
}
