<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 18.06.15
 * Time: 0:41
 */

namespace Mtt\BlogBundle\Command;

use Mtt\BlogBundle\Cron\CronChain;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xelbot\Telegram\Robot;

class CronDailyCommand extends Command
{
    /**
     * @var CronChain
     */
    private $chain;

    /**
     * @var Robot
     */
    private $bot;

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

    protected function configure()
    {
        $this
            ->setName('mtt:cron:daily')
            ->setDescription('Start daily crons');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = [];
        foreach ($this->chain->getDailyCrons() as $cronJob) {
            try {
                $cronJob->run();
                $messages[] = sprintf('%s: %s', self::getJobName($cronJob), $cronJob->getMessage());
                $output->writeln(
                    sprintf('<comment>%s:</comment> %s', self::getJobName($cronJob), $cronJob->getMessage())
                );
            } catch (\Exception $e) {
                $messages[] = sprintf('Error %s: %s', self::getJobName($cronJob), $e->getMessage());
                $output->writeln(
                    sprintf('<error>%s Error:</error> %s', self::getJobName($cronJob), $e->getMessage())
                );
            }
        }

        $this->bot->sendMessage(implode("\n", $messages));
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
