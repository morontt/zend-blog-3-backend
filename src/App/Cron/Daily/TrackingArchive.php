<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 01.09.17
 * Time: 0:54
 */

namespace App\Cron\Daily;

use App\Cron\DailyCronServiceInterface;
use App\Entity\Tracking;
use App\Repository\TrackingRepository;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Silarhi\CursorPagination\Configuration\OrderConfiguration;
use Silarhi\CursorPagination\Configuration\OrderConfigurations;
use Silarhi\CursorPagination\Pagination\CursorPagination;

class TrackingArchive implements DailyCronServiceInterface
{
    /**
     * @var int|string
     */
    private $rows;

    public function __construct(
        private EntityManagerInterface $em,
        private TrackingRepository $trackingRepo,
    ) {
    }

    /**
     * @throws DBALException
     */
    public function run(): void
    {
        $configurations = new OrderConfigurations(
            new OrderConfiguration('t.id', fn (Tracking $item) => $item->getId())
        );

        $to = (new DateTime())->sub(new DateInterval('P31D'));

        /** @var CursorPagination<Tracking> $pagination */
        $pagination = new CursorPagination(
            $this->trackingRepo->getDataToArchiveQuery($to),
            $configurations,
            100
        );

        $fileName = null;
        $oldFileName = null;
        $fp = null;
        $cnt = 0;
        foreach ($pagination->getChunkResults() as $results) {
            foreach ($results as $trackingItem) {
                $fileName = $this->fileName($trackingItem);
                if ($fileName !== $oldFileName) {
                    if ($fp !== null) {
                        $result = fclose($fp);
                        if (!$result) {
                            throw new RuntimeException('Failed to close file: ' . $oldFileName);
                        }
                    }

                    $addHeader = !file_exists($fileName);

                    $fp = fopen($fileName, 'a+');
                    if ($fp === false) {
                        throw new RuntimeException('Failed to create file: ' . $fileName);
                    }

                    if ($addHeader) {
                        fputcsv($fp, $this->archiveHeader());
                    }
                }

                $res = fputcsv($fp, $this->archiveData($trackingItem));
                if ($res === false) {
                    throw new RuntimeException('Failed to write data: ' . $fileName);
                }
                $cnt++;
            }
            $this->em->clear();
        }

        if ($fp !== null) {
            $result = fclose($fp);
            if (!$result) {
                throw new RuntimeException('Failed to close file: ' . $fileName);
            }
        }

        $this->trackingRepo->removeTracking($to);
        if ($cnt) {
            $this->rows = $cnt;
        }
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

    private function fileName(Tracking $item): string
    {
        return APP_VAR_DIR . '/tracking_archive/' . sprintf('tracking_%s.csv', $item->getTimeCreated()->format('Y_m'));
    }

    private function archiveData(Tracking $item): array
    {
        return [
            $item->getTimeCreated()->format(DateTimeInterface::RFC3339_EXTENDED),
            $item->getIpAddress(),
            $item->getStatusCode(),
            $item->getPost()?->getId() ?? '',
            $item->getRequestURI() ?? '',
            $item->getDuration() ?: '',
            $item->getMethod() ?? '',
            $item->isCdn() ? '+' : '',
            $item->getTrackingAgent()?->getUserAgent() ?? '',
        ];
    }

    private function archiveHeader(): array
    {
        return [
            'Time',
            'IP',
            'Status Code',
            'Article ID',
            'URI',
            'Duration',
            'Method',
            'CDN',
            'User Agent',
        ];
    }
}
