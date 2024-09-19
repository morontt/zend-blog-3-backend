<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:34
 */

namespace App\Cron\Daily;

use App\Cron\DailyCronServiceInterface;
use App\Service\DropboxService;
use Symfony\Component\Process\Process;

class DatabaseBackup implements DailyCronServiceInterface
{
    const DROPBOX_DUMPS_PATH = '/db_dumps';
    const DROPBOX_DUMPS_COUNT = 14;

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
        $this->clearOldDumps();

        $dumpPath = $this->getDumpPath();
        $process = new Process(
            sprintf(
                'mysqldump -h %s -u %s --password=%s %s | bzip2 > %s',
                $this->dbHost,
                $this->dbUser,
                escapeshellarg($this->dbPassword),
                $this->dbName,
                $dumpPath
            )
        );
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $this->dumpSize = filesize($dumpPath);

        $this->dropbox->upload($dumpPath, $this->getDropboxPath());
        unlink($dumpPath);
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return sprintf('%dKB', (int)($this->dumpSize / 1024));
    }

    private function clearOldDumps()
    {
        $dropboxFiles = $this->dropbox->filesByDir(self::DROPBOX_DUMPS_PATH);
        rsort($dropboxFiles);

        $cnt = 0;
        $delete = [];
        foreach ($dropboxFiles as $file) {
            if (preg_match('/\/\d+_' . $this->dbName . '\./', $file)) {
                $cnt++;
                if ($cnt >= self::DROPBOX_DUMPS_COUNT) {
                    $delete[] = $file;
                }
            }
        }

        foreach ($delete as $file) {
            $this->dropbox->delete($file);
        }
    }

    /**
     * @return string
     */
    private function getFilename(): string
    {
        $datetime = (new \DateTime())->format('YmdHi');

        return sprintf('%s_%s.sql.bz2', $datetime, $this->dbName);
    }

    /**
     * @return string
     */
    private function getDumpPath(): string
    {
        return APP_VAR_DIR . '/tmp/' . $this->getFilename();
    }

    /**
     * @return string
     */
    private function getDropboxPath(): string
    {
        return self::DROPBOX_DUMPS_PATH . '/' . $this->getFilename();
    }
}
