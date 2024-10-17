<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 09.05.16
 * Time: 0:50
 */

namespace App\Cron\Daily;

use App\Cron\DailyCronServiceInterface;
use App\Entity\MediaFile;
use App\Service\BackupService;
use App\Service\ImageManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ImagesBackup implements DailyCronServiceInterface
{
    /**
     * @var EntityManager
     */
    protected EntityManagerInterface $em;

    /**
     * @var int
     */
    protected int $countImported = 0;

    /**
     * @var BackupService
     */
    protected BackupService $backupService;

    /**
     * @param EntityManagerInterface $em
     * @param BackupService $backupService
     */
    public function __construct(EntityManagerInterface $em, BackupService $backupService)
    {
        $this->em = $em;
        $this->backupService = $backupService;
    }

    public function run(): void
    {
        $images = $this->em->getRepository(MediaFile::class)->getNotBackuped();

        if (count($images)) {
            foreach ($images as $image) {
                $this->backupService->upload(
                    ImageManager::getUploadsDir() . '/' . $image->getPath(),
                    BackupService::IMAGES_PATH . '/' . $image->getPath()
                );

                $image->setBackuped(true);
                $this->em->flush();

                $this->countImported++;
            }
        }
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        $message = 'Nothing';
        if ($this->countImported === 1) {
            $message = '1 new image';
        } elseif ($this->countImported > 1) {
            $message = $this->countImported . ' new images';
        }

        return $message;
    }
}
