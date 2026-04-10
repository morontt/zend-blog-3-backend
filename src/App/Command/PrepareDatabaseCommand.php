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
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'mtt:prepare:db',
    description: 'Prepare Database for tests',
)]
class PrepareDatabaseCommand extends Command
{
    private Connection $connection;

    public function __construct(
        EntityManagerInterface $em,
    ) {
        parent::__construct();

        $this->connection = $em->getConnection();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hash = $this->calculateDBStateHash();
        $dump = sprintf(
            '%s/dump_%s.sql',
            dirname(__DIR__, 3) . '/var/db_data',
            substr($hash, 0, 14)
        );

        if ($input->isInteractive()) {
            /** @var \Symfony\Component\Console\Helper\QuestionHelper */
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion(
                'This action will erase <comment>' . $this->connection->getDatabase()
                . '</comment> database. Continue? (y/n) ',
                false
            );

            if (!$helper->ask($input, $output, $question)) {
                return Command::SUCCESS;
            }
        }

        $dbDropInput = new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => true,
        ]);
        $dbDropInput->setInteractive(false);

        $output->writeln(sprintf('<comment>Drop database</comment>'));
        $this->getApplication()->doRun($dbDropInput, $output);

        $dbCreateInput = new ArrayInput([
            'command' => 'doctrine:database:create',
        ]);
        $dbCreateInput->setInteractive(false);

        $output->writeln(sprintf('<comment>Create database</comment>'));
        $this->getApplication()->doRun($dbCreateInput, $output);

        $migrationInput = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
        ]);
        $migrationInput->setInteractive(false);

        if (file_exists($dump) && is_file($dump)) {
            $this->restoreBackUp($dump);
            $output->writeln(sprintf('<info>Dump was restored:</info> <comment>%s</comment>', $dump));

            $this->runDBCommand(
                realpath(__DIR__ . '/../../../migrations/sql/drop_migrations.sql'),
                '/usr/bin/mysql %s < %s'
            );

            $output->writeln(sprintf('<comment>Apply migrations</comment>'));
            $this->getApplication()->doRun($migrationInput, $output);
        } else {
            $output->writeln(sprintf('<comment>Apply migrations</comment>'));
            $this->getApplication()->doRun($migrationInput, $output);

            $output->writeln(sprintf('<comment>Load fixtures</comment>'));
            $this->runCommand('php bin/console doctrine:fixtures:load --append --no-interaction');

            $this->createBackUp($dump);
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

    private function createBackUp(string $file): void
    {
        $this->runDBCommand($file, '/usr/bin/mysqldump %s > %s');
    }

    private function restoreBackUp(string $file): void
    {
        $this->runDBCommand($file, '/usr/bin/mysql %s < %s');
    }

    private function runDBCommand(string $file, string $formatCommand): void
    {
        $database = $this->connection->getDatabase();
        $params = $this->connection->getParams();

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
