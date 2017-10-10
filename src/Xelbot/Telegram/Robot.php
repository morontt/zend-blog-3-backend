<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 24.09.17
 * Time: 10:29
 */

namespace Xelbot\Telegram;

use Psr\Log\LoggerInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Entity\Update;
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
     * @var TelegramCommandInterface[]
     */
    protected $commands = [];

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
     * @param TelegramCommandInterface $command
     */
    public function addCommand(TelegramCommandInterface $command)
    {
        $this->commands[$command->getCommandName()] = $command;
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
    public function setWebhook(string $url, string $certificate = null): TelegramResponse
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
     * @return TelegramResponse
     */
    public function getWebhookInfo(): TelegramResponse
    {
        return $this->requester->getWebhookInfo();
    }

    /**
     * @return TelegramResponse
     */
    public function deleteWebhook(): TelegramResponse
    {
        return $this->requester->deleteWebhook();
    }

    /**
     * @param array $requestData
     *
     * @return bool
     */
    public function handle(array $requestData)
    {
        if ($this->logger) {
            $this->logger->info('Webhook: ', $requestData);
        }

        $normalizer = new ObjectNormalizer(
            null,
            new CamelCaseToSnakeCaseNameConverter(),
            null,
            new ReflectionExtractor()
        );

        $serializer = new Serializer([$normalizer]);
        $obj = $serializer->denormalize($requestData, Update::class);

        dump($obj);

        return true;
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
