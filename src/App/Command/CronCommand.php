<?php

declare(strict_types=1);

namespace App\Command;

use App\Cron\CronChain;
use App\Cron\CronServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Xelbot\Telegram\Robot;

abstract class CronCommand extends Command
{
    /**
     * @return CronServiceInterface[]
     */
    abstract protected function getCrons(): array;

    public function __construct(
        protected CronChain $chain,
        private Robot $bot,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('list', null, InputOption::VALUE_NONE, 'List of crons')
            ->addOption('cron', null, InputOption::VALUE_REQUIRED, 'Cron name for execution')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('list')) {
            $output->writeln('<comment>List of crons:</comment>');
            foreach ($this->getCrons() as $cronJob) {
                $output->writeln('  ' . self::getJobName($cronJob));
            }

            return Command::SUCCESS;
        }

        $cronName = $input->getOption('cron');
        $cronFound = false;

        $messages = [];
        foreach ($this->getCrons() as $cronJob) {
            try {
                if ($cronName && $cronName !== self::getJobName($cronJob)) {
                    continue;
                }

                $cronJob->run();
                if ($cronJob->getMessage()) {
                    $messages[] = sprintf('%s: %s', self::getJobName($cronJob), $cronJob->getMessage());
                    $output->writeln(
                        sprintf('<comment>%s:</comment> %s', self::getJobName($cronJob), $cronJob->getMessage())
                    );
                } else {
                    $output->writeln(
                        sprintf('<comment>%s:</comment> without message', self::getJobName($cronJob))
                    );
                }

                $cronFound = true;
            } catch (Throwable $e) {
                $messages[] = sprintf(
                    'Error %s: %s, file: %s, line: %d',
                    self::getJobName($cronJob),
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                );
                $output->writeln(
                    sprintf(
                        '<error>%s Error:</error> %s, file: %s, line: %d',
                        self::getJobName($cronJob),
                        $e->getMessage(),
                        $e->getFile(),
                        $e->getLine()
                    )
                );
            }
        }

        if ($cronName && !$cronFound) {
            $output->writeln([
                '',
                "<error>Cron \"{$cronName}\" not found<error>",
                '',
            ]);
        }

        if (count($messages)) {
            $this->bot->sendMessage(implode("\n", $messages));
        }

        return Command::SUCCESS;
    }

    protected static function getJobName(CronServiceInterface $cronJob): string
    {
        // https://stackoverflow.com/a/27457689/6109406
        return substr(strrchr(get_class($cronJob), '\\'), 1);
    }
}
