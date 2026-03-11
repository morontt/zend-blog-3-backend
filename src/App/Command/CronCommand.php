<?php

namespace App\Command;

use App\Cron\CronChain;
use App\Cron\CronServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $messages = [];
        foreach ($this->getCrons() as $cronJob) {
            try {
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
            } catch (Throwable $e) {
                $messages[] = sprintf('Error %s: %s', self::getJobName($cronJob), $e->getMessage());
                $output->writeln(
                    sprintf('<error>%s Error:</error> %s', self::getJobName($cronJob), $e->getMessage())
                );
            }
        }

        if (count($messages)) {
            $this->bot->sendMessage(implode("\n", $messages));
        }

        return 0;
    }

    protected static function getJobName(CronServiceInterface $cronJob): string
    {
        // https://stackoverflow.com/a/27457689/6109406
        return substr(strrchr(get_class($cronJob), '\\'), 1);
    }
}
