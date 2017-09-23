<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 02.09.17
 * Time: 20:38
 */

namespace Mtt\BlogBundle\Telegram;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

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
     * @param string $token
     * @param string $botName
     * @param int $adminId
     */
    public function __construct(string $token, string $botName, int $adminId)
    {
        $this->telegram = new Telegram($token, $botName);
        $this->telegram->enableAdmin($adminId);

        $this->telegram->addCommandsPath(realpath(__DIR__ . '/../../../Longman/TelegramBot/Commands'));

        $this->adminId = $adminId;
    }

    /**
     * @param string $message
     */
    public function sendMessage(string $message)
    {
        Request::sendMessage([
            'chat_id' => $this->adminId,
            'text' => $message,
        ]);
    }

    public function getUpdates()
    {
        $response = Request::getUpdates([
            'offset' => null,
            'limit' => 50,
            'timeout' => null,
        ]);

        if ($response->isOk()) {
            /** @var \Longman\TelegramBot\Entities\Update $result */
            foreach ($response->getResult() as $result) {
                var_dump($result);
            }
        }
    }

    /**
     * @param string $url
     * @param string|null $certificate
     *
     * @return ServerResponse
     */
    public function setWebhook(string $url, string $certificate = null): ServerResponse
    {
        $data = [];
        if ($certificate) {
            $data['certificate'] = $certificate;
        }

        return $this->telegram->setWebhook($url, $data);
    }

    /**
     * @return bool
     */
    public function handle()
    {
        return $this->telegram->handle();
    }
}
