<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:34
 */

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\Bundle\DoctrineBundle\Registry;
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
     * @var EntityManager
     */
    protected $em;

    /**
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     * @param Registry $doctrine
     */
    public function __construct($dbName, $dbUser, $dbPassword, Registry $doctrine)
    {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;

        $this->em = $doctrine->getManager();
    }

    public function run()
    {
        $process = new Process(
            sprintf(
                'mysqldump -h localhost -u %s --password=%s %s | gzip > %s',
                $this->dbUser,
                $this->dbPassword,
                $this->dbName,
                $this->getDumpPath()
            )
        );
        $process->run();

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
    protected function getFilename()
    {
        $datetime = (new \DateTime('now'))->format('Ymd');

        return sprintf('%s_%s.sql.gz', $datetime, $this->dbName);
    }

    /**
     * @return string
     */
    protected function getDumpPath()
    {
        return realpath(__DIR__ . '/../../../../../var/tmp') . '/' . $this->getFilename();
    }
}
