<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 09.04.2026
 * Time: 17:55
 */

namespace App\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'mtt:prepare:db',
    description: 'Prepare Database for tests',
)]
class PrepareDatabaseCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hash = $this->calculateDBStateHash();
        $dump = sprintf(
            '%s/dump_%s.sql',
            dirname(__DIR__, 3) . '/var/db_data',
            substr($hash, 0, 14)
        );

        $connection = $this->em->getConnection();

        $input = new ArrayInput(['--force' => true]);
        $input->setInteractive(false);

        $output->writeln(sprintf('<comment>Drop database</comment>'));
        $command = $this->getApplication()->find('doctrine:database:drop');
        $command->run($input, $output);

        $input = new ArrayInput([]);
        $input->setInteractive(false);

        $output->writeln(sprintf('<comment>Create database</comment>'));
        $command = $this->getApplication()->find('doctrine:database:create');
        $command->run($input, $output);

        if (file_exists($dump) && is_file($dump)) {
            $this->restoreBackUp($connection, $dump);
            $output->writeln(sprintf('<info>Dump was restored:</info> <comment>%s</comment>', $dump));

            $this->runDBCommand(
                $connection,
                realpath(__DIR__ . '/../../../migrations/sql/drop_migrations.sql'),
                '/usr/bin/mysql %s < %s'
            );

            $output->writeln(sprintf('<comment>Apply migrations</comment>'));
            $command = $this->getApplication()->find('doctrine:migrations:migrate');
            $command->run($input, $output);
        } else {
            $output->writeln(sprintf('<comment>Apply migrations</comment>'));
            $command = $this->getApplication()->find('doctrine:migrations:migrate');
            $command->run($input, $output);

            $output->writeln(sprintf('<comment>Load fixtures</comment>'));
            $this->runCommand('php bin/console doctrine:fixtures:load --append --no-interaction');

            $this->createBackUp($connection, $dump);
            $output->writeln(sprintf('<info>Dump was created:</info> <comment>%s</comment>', $dump));
        }

        return Command::SUCCESS;
    }

    private function calculateDBStateHash(): string
    {
        $hash = $this->getHashByDirectory(realpath(__DIR__ . '/../../../migrations'));

        return $this->getHashByDirectory(realpath(__DIR__ . '/../DataFixtures'), $hash);
    }

    private function getHashByDirectory(string $directory, string $hash = 'init'): string
    {
        $files = array_filter(scandir($directory), function ($fname) {
            return preg_match('/\.php$/', $fname);
        });
        sort($files);

        foreach ($files as $file) {
            $fileName = $directory . '/' . $file;
            $hash = sha1($hash . sha1_file($fileName));
        }

        return $hash;
    }

    private function createBackUp(Connection $connection, string $file): void
    {
        $this->runDBCommand($connection, $file, '/usr/bin/mysqldump %s > %s');
    }

    private function restoreBackUp(Connection $connection, string $file): void
    {
        $this->runDBCommand($connection, $file, '/usr/bin/mysql %s < %s');
    }

    private function runDBCommand(Connection $connection, string $file, string $formatCommand): void
    {
        $database = $connection->getDatabase();
        $params = $connection->getParams();

        $command = sprintf(
            $formatCommand,
            escapeshellarg($database),
            escapeshellarg($file)
        );

        if (isset($params['host']) && strlen($params['host'])) {
            $command .= sprintf(' --host=%s', escapeshellarg($params['host']));
        }

        if (isset($params['user']) && strlen($params['user'])) {
            $command .= sprintf(' --user=%s', escapeshellarg($params['user']));
        }

        if (isset($params['password']) && strlen($params['password'])) {
            $command .= sprintf(' --password=%s', escapeshellarg($params['password']));
        }

        if (isset($params['port'])) {
            $command .= sprintf(' -P%s', escapeshellarg((string)$params['port']));
        }

        $this->runCommand($command);
    }

    private function runCommand(string $command): void
    {
        $process = Process::fromShellCommandline($command, timeout: null);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }
    }
}
