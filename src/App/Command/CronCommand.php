<?php

namespace App\Command;

use App\Cron\CronChain;
use App\Cron\CronServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xelbot\Telegram\Robot;

abstract class CronCommand extends Command
{
    /**
     * @return CronServiceInterface[]
     */
    abstract protected function getCrons(): array;

    /**
     * @var CronChain
     */
    protected CronChain $chain;

    /**
     * @var Robot
     */
    private Robot $bot;

    /**
     * @param CronChain $chain
     * @param Robot $bot
     */
    public function __construct(CronChain $chain, Robot $bot)
    {
        parent::__construct();

        $this->chain = $chain;
        $this->bot = $bot;
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
            } catch (\Exception $e) {
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

    /**
     * @param $cronJob
     *
     * @return string
     */
    protected static function getJobName($cronJob): string
    {
        $classParts = explode('\\', get_class($cronJob));

        return $classParts[count($classParts) - 1];
    }
}
