<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:34
 */

namespace App\Cron\Daily;

use App\Cron\DailyCronServiceInterface;
use App\Service\BackupService;
use DateTime;
use RuntimeException;
use Symfony\Component\Process\Process;

class DatabaseBackup implements DailyCronServiceInterface
{
    /**
     * @var string
     */
    protected string $dbName;

    /**
     * @var string
     */
    protected string $dbUser;

    /**
     * @var string
     */
    protected string $dbPassword;

    /**
     * @var string
     */
    protected string $dbHost;

    /**
     * @var int
     */
    protected int $dumpSize = 0;

    /**
     * @var BackupService
     */
    protected BackupService $backupService;

    /**
     * @param string $dbHost
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     * @param BackupService $backupService
     */
    public function __construct(
        string $dbHost,
        string $dbName,
        string $dbUser,
        string $dbPassword,
        BackupService $backupService
    ) {
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;

        $this->backupService = $backupService;
    }

    public function run(): void
    {
        $this->clearOldDumps();

        $dumpPath = $this->getDumpPath();
        $process = Process::fromShellCommandline(
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
            throw new RuntimeException($process->getErrorOutput());
        }

        $this->dumpSize = filesize($dumpPath);

        $this->backupService->upload($dumpPath, $this->getBackupPath());
        unlink($dumpPath);
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return sprintf('%dKB', (int)($this->dumpSize / 1024));
    }

    private function clearOldDumps(): void
    {
        $backedFiles = $this->backupService->filesByDir(BackupService::DUMPS_PATH);
        rsort($backedFiles);

        $cnt = 0;
        $delete = [];
        foreach ($backedFiles as $file) {
            if (preg_match('/\/\d+_' . $this->dbName . '\./', $file)) {
                $cnt++;
                if ($cnt >= BackupService::DUMPS_COUNT) {
                    $delete[] = $file;
                }
            }
        }

        foreach ($delete as $file) {
            $this->backupService->delete($file);
        }
    }

    /**
     * @return string
     */
    private function getFilename(): string
    {
        $datetime = (new DateTime())->format('YmdHi');

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
    private function getBackupPath(): string
    {
        return BackupService::DUMPS_PATH . '/' . $this->getFilename();
    }
}
