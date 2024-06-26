<?php

namespace Mtt\BlogBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Service\ImageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImagesClearThumbs extends Command
{
    private EntityManagerInterface $em;

    private ImageManager $im;

    public function __construct(EntityManagerInterface $em, ImageManager $im)
    {
        parent::__construct();

        $this->em = $em;
        $this->im = $im;
    }

    protected function configure()
    {
        $this
            ->setName('mtt:images:clear-thumbs')
            ->setDescription('Clear thumbs of images')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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
    }
}
