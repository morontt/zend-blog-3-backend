<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 06.10.2026
 * Time: 19:26
 */

namespace App\Command\Gearman;

use App\LogTrait;
use App\Worker\TaskChain;
use GearmanWorker;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'mtt:gearman:worker',
    description: 'Gearman worker',
)]
class WorkerCommand extends Command
{
    use LogTrait;

    private string $host;
    private int $port;

    public function __construct(
        string $host,
        string $port,
        private TaskChain $chain,
        LoggerInterface $logger,
    ) {
        parent::__construct();

        $this->host = $host;
        $this->port = (int)$port;

        $this->setLogger($logger);
    }

    protected function configure(): void
    {
        $this
            ->addOption('max-time', null, InputOption::VALUE_OPTIONAL, 'Max work time', 3600)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $worker = new GearmanWorker();
        $worker->addServer($this->host, $this->port);

        foreach ($this->chain->getTaskExecutors() as $executor) {
            $worker->addFunction(
                $executor->getTaskName(),
                [$executor, 'execute'],
                null,
                $executor->getTimeout(),
            );
        }

        $stop = time() + (int)$input->getOption('max-time');
        $output->writeln('<info>Waiting for job...</info>');

        while ($worker->work()) {
            if ($worker->returnCode() != GEARMAN_SUCCESS) {
                $output->writeln('<info>return_code:</info> ' . $worker->returnCode());
                break;
            }

            if (time() > $stop) {
                $output->writeln('<info>Stop by timeout</info>');
                break;
            }
        }

        return Command::SUCCESS;
    }
}
