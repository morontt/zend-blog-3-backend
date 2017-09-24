<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 02.09.17
 * Time: 20:38
 */

namespace Mtt\BlogBundle\Telegram;

use Longman\TelegramBot\Telegram;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Xelbot\Telegram\Robot;
use Xelbot\Telegram\TelegramResponse;

class Bot
{
    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * @var int
     */
    protected $adminId;

    /**
     * @var Robot
     */
    protected $robot;

    /**
     * @param string $token
     * @param string $botName
     * @param int $adminId
     * @param string $logsDir
     */
    public function __construct(string $token, string $botName, int $adminId, string $logsDir)
    {
        $this->telegram = new Telegram($token, $botName);
        $this->telegram->enableAdmin($adminId);

        $this->telegram->addCommandsPath(realpath(__DIR__ . '/../../../Longman/TelegramBot/Commands'));

        $this->adminId = $adminId;

        $this->robot = new Robot($token, $botName, $adminId);

        $loggerHandler = new RotatingFileHandler($logsDir . '/telegram.log', 14);
        $loggerHandler->setFormatter(new LineFormatter(null, 'Y-m-d H:i:s.v'));
        $logger = new Logger('telegram', [$loggerHandler]);

        $this->robot->setLogger($logger);
    }

    /**
     * @param string $message
     *
     * @return TelegramResponse
     */
    public function sendMessage(string $message): TelegramResponse
    {
        return $this->robot->sendMessage($message);
    }

    /**
     * @param string $url
     * @param string|null $certificate
     *
     * @return TelegramResponse
     */
    public function setWebhook(string $url, string $certificate = null): TelegramResponse
    {
        return $this->robot->setWebhook($url, $certificate);
        // return $this->telegram->deleteWebhook();
    }

    /**
     * @return bool
     */
    public function handle()
    {
        return $this->telegram->handle();
    }
}
