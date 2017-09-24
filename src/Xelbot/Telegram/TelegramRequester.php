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
use Psr\Log\LoggerInterface;

class TelegramRequester
{
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
    protected $logger = null;

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
    public function setLogger(LoggerInterface $logger = null)
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
     * @param array $data
     *
     * @return TelegramResponse
     */
    public function setWebhook(array $data): TelegramResponse
    {
        return $this->send('setWebhook', $data);
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

        try {
            $response = $this->client->post(
                sprintf('/bot%s/%s', $this->token, $action),
                $this->prepareRequestParams($data)
            );
            $result = (string)$response->getBody();
        } catch (RequestException $e) {
            $result = ($e->getResponse()) ? (string)$e->getResponse()->getBody() : '';
        }

        $responseData = json_decode($result, true);
        if ($this->logger) {
            $this->logger->info('Response: ' . $action, $responseData);
        }

        return new TelegramResponse($responseData);
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
