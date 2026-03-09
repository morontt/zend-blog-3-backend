<?php

namespace App\Command;

use App\Entity\MediaFile;
use App\Service\ImageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImagesClearThumbs extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private ImageManager $im,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('mtt:images:clear-thumbs')
            ->setDescription('Clear thumbs of images')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $i = 0;
        $repository = $this->em->getRepository(MediaFile::class);
        foreach ($repository->findAll() as $mediaFile) {
            $i++;
            $this->im->removeAllPreview($mediaFile);
            $output->writeln(
                sprintf('%d. <comment>%s</comment> was cleared', $i, $mediaFile->getOriginalFileName())
            );
        }

        return 0;
    }
}
