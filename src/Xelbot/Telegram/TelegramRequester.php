<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 24.09.17
 * Time: 11:15
 */

namespace Xelbot\Telegram;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use JsonException;
use Psr\Log\LoggerInterface;
use Throwable;
use Xelbot\Telegram\Exception\TelegramException;

/**
 * @method TelegramResponse getWebhookInfo()
 * @method TelegramResponse deleteWebhook()
 * @method TelegramResponse setWebhook(array $data)
 */
class TelegramRequester
{
    /**
     * @var array
     */
    public static $availableMethods = [
        'setWebhook',
        'getWebhookInfo',
        'deleteWebhook',
    ];

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $token = '';

    /**
     * @var LoggerInterface|null
     */
    protected $logger;

    /**
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
        $this->client = new Client(
            [
                'base_uri' => 'https://api.telegram.org',
            ]
        );
    }

    /**
     * @param LoggerInterface|null $logger
     */
    public function setLogger(?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $data
     *
     * @return TelegramResponse
     */
    public function sendMessage(array $data): TelegramResponse
    {
        $text = $data['text'];

        do {
            $data['text'] = mb_substr($text, 0, 4096);
            $response = $this->send('sendMessage', $data);

            $text = mb_substr($text, 4096);
        } while (mb_strlen($text, 'UTF-8') > 0);

        return $response;
    }

    /**
     * @param $method
     * @param $args
     *
     * @throws TelegramException
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (in_array($method, self::$availableMethods)) {
            array_unshift($args, $method);

            return call_user_func_array([$this, 'send'], $args);
        }

        throw new TelegramException('Undefined method: ' . $method);
    }

    /**
     * @param string $action
     * @param array $data
     *
     * @return TelegramResponse
     */
    protected function send(string $action, array $data = []): TelegramResponse
    {
        if ($this->logger) {
            $this->logger->info('Request: ' . $action, $data);
        }

        $responseBody = null;
        $context = [];
        try {
            $response = $this->client->post(
                sprintf('/bot%s/%s', $this->token, $action),
                $this->prepareRequestParams($data)
            );
            $responseBody = (string)$response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $responseBody = (string)$e->getResponse()->getBody();
            } else {
                $context = ['RequestException' => $e->getMessage()];
            }
        } catch (Throwable $e) {
            $context = ['Error' => $e->getMessage()];
        }

        $responseData = null;
        if ($responseBody) {
            try {
                $responseData = json_decode($responseBody, true, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                $context = ['JsonException' => $e->getMessage()];
            }
        }

        if ($this->logger) {
            $this->logger->info('Response: ' . $action, $responseData ?: $context);
        }

        return new TelegramResponse($responseData ?: []);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function prepareRequestParams(array $params)
    {
        $hasResource = false;
        $multipart = [];

        foreach ($params as $key => $item) {
            $hasResource |= is_resource($item);
            $multipart[] = [
                'name' => $key,
                'contents' => $item,
            ];
        }

        if ($hasResource) {
            return [
                'multipart' => $multipart,
            ];
        }

        return [
            'form_params' => $params,
        ];
    }
}
