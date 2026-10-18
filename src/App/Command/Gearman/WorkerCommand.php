<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 06.10.2026
 * Time: 19:26
 */

namespace App\Command\Gearman;

use App\LogTrait;
use GearmanJob;
use GearmanWorker;
use JsonException;
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

        $worker->addFunction('tracking_activity', [$this, 'saveTrackingActivity'], null, 10);

        $output->writeln('<info>Waiting for job...</info>');

        while ($worker->work()) {
            if ($worker->returnCode() != GEARMAN_SUCCESS) {
                $output->writeln('<info>return_code:</info> ' . $worker->returnCode());
                break;
            }
        }

        return Command::SUCCESS;
    }

    public function saveTrackingActivity(GearmanJob $job): void
    {
        try {
            $data = json_decode($job->workload(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->error('decode error', [$e->getMessage()]);
            $job->sendFail();

            return;
        }

        $this->debug('tracking data', $data);

        $job->sendComplete('');
    }
}
