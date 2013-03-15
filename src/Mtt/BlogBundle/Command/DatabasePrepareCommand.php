<?php

namespace Mtt\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class DatabasePrepareCommand extends ContainerAwareCommand
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    protected function configure()
    {
        $this->setName('mtt:database:prepare')
            ->setDescription('Preparation DB from testing')
            ->addOption('without-fixtures', null, InputOption::VALUE_NONE, 'If set, fixtures is not loaded');
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('env') != 'test') {
            $output->writeln("<error>This operation cannot be executed in a test environment.</error>");
        } else {
            $this->getApplication()->setAutoExit(false);
            $this->setOutput($output);

            $this->dropDb();

            $connection = $this->getContainer()->get('doctrine')->getConnection();
            if ($connection->isConnected()) {
                $connection->close();
            }

            $this->createDb();
            $this->schemaDb();

            if (!$input->getOption('without-fixtures')) {
                $this->fixturesDb();
            }

        }
    }

    /**
     * @return int|string
     */
    protected function dropDb()
    {
        $command = $this->getApplication()->find('doctrine:database:drop');

        $arguments = array(
            'command' => 'doctrine:database:drop',
            '--force' => true,
        );

        return $command->run(new ArrayInput($arguments), $this->getOutput());
    }

    /**
     * @return int|string
     */
    protected function createDb()
    {
        $command = $this->getApplication()->find('doctrine:database:create');

        $arguments = array(
            'command' => 'doctrine:database:create',
        );

        return $command->run(new ArrayInput($arguments), $this->getOutput());
    }

    /**
     * @return int|string
     */
    protected function schemaDb()
    {
        $command = $this->getApplication()->find('doctrine:schema:create');

        $arguments = array(
            'command' => 'doctrine:schema:create',
        );

        return $command->run(new ArrayInput($arguments), $this->getOutput());
    }

    /**
     * @return int|string
     */
    protected function fixturesDb()
    {
        $command = $this->getApplication()->find('doctrine:fixtures:load');

        $arguments = array(
            'command' => 'doctrine:fixtures:load'
        );

        $input = new ArrayInput($arguments);
        $input->setInteractive(false);
        $command->run($input, $this->getOutput());
    }

}