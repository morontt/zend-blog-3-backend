<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 24.09.17
 * Time: 10:29
 */

namespace Xelbot\Telegram;

use Psr\Log\LoggerInterface;
use Xelbot\Telegram\Exception\TelegramException;

class Robot
{
    /**
     * @var string
     */
    protected $token = '';

    /**
     * @var string
     */
    protected $botName = '';

    /**
     * @var int
     */
    protected $adminId;

    /**
     * @var TelegramRequester
     */
    protected $requester;

    /**
     * @var LoggerInterface|null
     */
    protected $logger = null;

    /**
     * @param string $token
     * @param string $botName
     * @param int $adminId
     *
     * @throws TelegramException
     */
    public function __construct(string $token, string $botName, int $adminId)
    {
        if (!$token) {
            throw new TelegramException('API KEY not defined');
        }

        if (!preg_match('/\d+\:[\w\-]+/', $token)) {
            throw new TelegramException('Invalid API KEY');
        }

        $this->token = $token;
        $this->adminId = $adminId;

        if (!$botName) {
            $this->botName = $botName;
        }

        $this->requester = new TelegramRequester($token);
    }

    /**
     * @param LoggerInterface|null $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->requester->setLogger($logger);
    }

    /**
     * @param string $message
     * @param int|null $chatId
     *
     * @return TelegramResponse
     */
    public function sendMessage(string $message, int $chatId = null)
    {
        if ($chatId === null) {
            $chatId = $this->adminId;
        }

        return $this->requester->sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }

    /**
     * @param string $url
     * @param string|null $certificate
     *
     * @return TelegramResponse
     */
    public function setWebhook(string $url, string $certificate = null)
    {
        $data = [
            'url' => $url,
        ];

        if ($certificate) {
            $data['certificate'] = $this->getResource($certificate);
        }

        return $this->requester->setWebhook($data);
    }

    /**
     * @param string $file
     *
     * @return resource
     *
     * @throws TelegramException
     */
    protected function getResource(string $file)
    {
        $fp = fopen($file, 'rb');
        if ($fp === false) {
            throw new TelegramException('Cannot open ' . $file);
        }

        return $fp;
    }
}
