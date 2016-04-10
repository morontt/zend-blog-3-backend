<?php

namespace Mtt\BlogBundle\Command;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Entity\MediaFile;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AmazonImportCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var EntityManager
     */
    protected $em;

    protected function configure()
    {
        $this
            ->setName('mtt:amazon:import')
            ->setDescription('Import existing image from Amazon S3')
        ;
    }

    /**
     * SQL-query after first import
     *
     * UPDATE `media_file` AS `mf` SET `mf`.`post_id` = (
     *   SELECT `p`.`id` FROM `posts` AS `p`
     *   WHERE `p`.`text_post` LIKE CONCAT('%', `mf`.`path`, '%')
     * );
     *
     * CREATE TEMPORARY TABLE `mf_id` (`id` INT NOT NULL);
     * INSERT INTO `mf_id` SELECT MIN(`i`.`id`) FROM `media_file` AS `i`
     *   WHERE `i`.`post_id` IS NOT NULL GROUP BY `i`.`post_id`;
     * UPDATE `media_file` AS `mf` SET `mf`.`default_image` = 1 WHERE `mf`.`id` IN (
     *   SELECT `i`.`id` FROM `mf_id` AS `i`
     * );
     * DROP TEMPORARY TABLE `mf_id`;
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->em->getConfiguration()->setSQLLogger(null);

        /* @var \League\Flysystem\Filesystem $fs */
        $fs = $this->getContainer()->get('mtt_blog.image_manager')->getRemoteFs();

        $dirs = [];
        foreach ($fs->listContents() as $item) {
            if ($item['type'] == 'file') {
                $this->saveFile($item);
            } else {
                $dirs[] = $item['path'];
            }
        }

        foreach ($dirs as $dir) {
            foreach ($fs->listContents($dir, true) as $item) {
                if ($item['type'] == 'file') {
                    $this->saveFile($item);
                }
            }
        }

        $output->writeln('');
        $output->writeln('<info>Import complete</info>');

        $endTime = microtime(true);

        $output->writeln(
            sprintf('<info>Total time: <comment>%s</comment> sec</info>', round($endTime - $startTime, 3))
        );
    }

    /**
     * @param array $data
     */
    protected function saveFile(array $data)
    {
        $media = $this->em->getRepository('MttBlogBundle:MediaFile')->findOneBy(['path' => $data['path']]);
        if (!$media) {
            $media = new MediaFile();

            $time = \DateTime::createFromFormat('U', $data['timestamp']);

            $media
                ->setTimeCreated($time)
                ->setLastUpdate($time)
                ->setFileSize($data['size'])
                ->setPath($data['path'])
            ;

            $this->em->persist($media);
            $this->em->flush();

            $this->output->writeln(sprintf('file: <comment>%s</comment>', $data['path']));
        }
    }
}
