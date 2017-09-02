<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:34
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\ORM\EntityManager;
use Dropbox;
use Mtt\BlogBundle\Cron\CronServiceInterface;
use Mtt\BlogBundle\Entity\SystemParameters;
use Symfony\Component\Process\Process;

class DatabaseBackup implements CronServiceInterface
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
     * @var EntityManager
     */
    protected $em;

    /**
     * @var int
     */
    protected $dumpSize = 0;

    /**
     * @param string $dbHost
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     * @param EntityManager $em
     */
    public function __construct(string $dbHost, string $dbName, string $dbUser, string $dbPassword, EntityManager $em)
    {
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;

        $this->em = $em;
    }

    public function run()
    {
        $process = new Process(
            sprintf(
                'mysqldump -h %s -u %s --password=%s %s | bzip2 > %s',
                $this->dbHost,
                $this->dbUser,
                $this->dbPassword,
                $this->dbName,
                $this->getDumpPath()
            )
        );
        $process->run();

        $this->dumpSize = filesize($this->getDumpPath());

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        /* @var SystemParameters $sp */
        $sp = $this->em->getRepository('MttBlogBundle:SystemParameters')
            ->findOneByOptionKey(SystemParameters::DROPBOX_TOKEN);

        if ($sp) {
            $tokenData = unserialize($sp->getValue());

            $dbxClient = new Dropbox\Client($tokenData['access_token'], 'ZendBlog-Backuper/0.1');

            $f = fopen($this->getDumpPath(), 'rb');
            $dbxClient->uploadFile('/' . $this->getFilename(), Dropbox\WriteMode::add(), $f);
            fclose($f);
            unlink($this->getDumpPath());
        }
    }

    /**
     * @return string
     */
    public function getMessage(): string
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
