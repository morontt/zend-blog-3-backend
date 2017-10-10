<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 02.09.17
 * Time: 20:38
 */

namespace Mtt\BlogBundle\Telegram;

use Longman\TelegramBot\Telegram;
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
     * @param Robot $robot
     */
    public function __construct(string $token, string $botName, int $adminId, Robot $robot)
    {
        $this->telegram = new Telegram($token, $botName);
        $this->telegram->enableAdmin($adminId);

        $this->telegram->addCommandsPath(realpath(__DIR__ . '/../../../Longman/TelegramBot/Commands'));

        $this->adminId = $adminId;

        $this->robot = $robot;
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
    }

    /**
     * @return TelegramResponse
     */
    public function getWebhookInfo(): TelegramResponse
    {
        return $this->robot->getWebhookInfo();
    }

    /**
     * @return TelegramResponse
     */
    public function deleteWebhook(): TelegramResponse
    {
        return $this->robot->deleteWebhook();
    }

    /**
     * @param array $requestData
     *
     * @return bool
     */
    public function handle(array $requestData)
    {
        $this->robot->handle($requestData);

        return $this->telegram->handle();
    }
}
