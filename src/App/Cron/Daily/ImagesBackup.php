<?php

declare(strict_types=1);

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
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;

class ImagesBackup implements DailyCronServiceInterface
{
    private int $countImported = 0;
    private int $countError = 0;

    public function __construct(
        private EntityManagerInterface $em,
        private BackupService $backupService,
    ) {
    }

    public function run(): void
    {
        /** @var MediaFile[] $images */
        $images = $this->em->getRepository(MediaFile::class)->getNotBackedUp();
        if (count($images)) {
            foreach ($images as $image) {
                $remotePath = BackupService::IMAGES_PATH . '/' . $image->getPath();
                if (!$this->backupService->fileExists($remotePath)) {
                    try {
                        $this->backupService->upload(
                            ImageManager::getUploadsDir() . '/' . $image->getPath(),
                            BackupService::IMAGES_PATH . '/' . $image->getPath()
                        );

                        $this->countImported++;
                        $image->setBackedUp(true);
                        $this->em->flush();
                    } catch (FilesystemException $e) {
                        $this->countError++;
                    }
                } else {
                    $image->setBackedUp(true);
                    $this->em->flush();
                }
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

        if ($this->countError > 0) {
            $message .= ', errors: ' . $this->countError;
        }

        return $message;
    }
}
