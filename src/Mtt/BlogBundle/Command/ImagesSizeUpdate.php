<?php

namespace Mtt\BlogBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Model\Image;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImagesSizeUpdate extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('mtt:images:update-size')
            ->setDescription('Update image size for `media_file` table')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rows = [];
        $repository = $this->em->getRepository(MediaFile::class);
        foreach ($repository->findAll() as $mediaFile) {
            if ($mediaFile->isImage()) {
                $image = new Image($mediaFile);
                $geometry = $image->getImageGeometry();

                $mediaFile
                    ->setWidth($geometry->width)
                    ->setHeight($geometry->height)
                ;

                $pathInfo = pathinfo($mediaFile->getPath());
                $rows[] = [$pathInfo['basename'], $geometry->width, $geometry->height];
                $this->em->flush();
            }
        }

        if (count($rows)) {
            $table = new Table($output);
            $table->setHeaders(['File', 'width', 'height']);

            $table->setRows($rows)->render();
        } else {
            $output->writeln('Nothing to show');
        }
    }
}
