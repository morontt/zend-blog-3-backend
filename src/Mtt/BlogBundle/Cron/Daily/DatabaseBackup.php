<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:34
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Mtt\BlogBundle\Cron\DailyCronServiceInterface;
use Mtt\BlogBundle\Service\DropboxService;
use Symfony\Component\Process\Process;

class DatabaseBackup implements DailyCronServiceInterface
{
    /**
     * @var string
     */
    protected $dbName;

    /**
     * @var string
     */
    protected $dbUser;

    /**
     * @var string
     */
    protected $dbPassword;

    /**
     * @var string
     */
    protected $dbHost;

    /**
     * @var int
     */
    protected $dumpSize = 0;

    /**
     * @var DropboxService
     */
    protected $dropbox;

    /**
     * @param string $dbHost
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     * @param DropboxService $dropbox
     */
    public function __construct(
        string $dbHost,
        string $dbName,
        string $dbUser,
        string $dbPassword,
        DropboxService $dropbox
    ) {
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;

        $this->dropbox = $dropbox;
    }

    public function run()
    {
        $dumpPath = $this->getDumpPath();

        $process = new Process(
            sprintf(
                'mysqldump -h %s -u %s --password=%s %s | bzip2 > %s',
                $this->dbHost,
                $this->dbUser,
                $this->dbPassword,
                $this->dbName,
                $dumpPath
            )
        );
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $this->dumpSize = filesize($dumpPath);

        $this->dropbox->uploadChunked($dumpPath, '/db_dumps/' . $this->getFilename());
        unlink($dumpPath);
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return sprintf('%dKB', (int)($this->dumpSize / 1024));
    }

    /**
     * @return string
     */
    protected function getFilename()
    {
        $datetime = (new \DateTime())->format('Ymd');

        return sprintf('%s_%s.sql.bz2', $datetime, $this->dbName);
    }

    /**
     * @return string
     */
    protected function getDumpPath()
    {
        return realpath(__DIR__ . '/../../../../../var/tmp') . '/' . $this->getFilename();
    }
}
