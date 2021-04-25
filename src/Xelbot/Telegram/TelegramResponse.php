<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 24.09.17
 * Time: 14:03
 */

namespace Xelbot\Telegram;

use Xelbot\Telegram\Exception\TelegramException;

/**
 * @method string getDescription()
 */
class TelegramResponse
{
    /**
     * @var bool
     */
    protected $ok;

    /**
     * @var array|null
     */
    protected $result;

    /**
     * @var array
     */
    protected $responseData = [];

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->ok = isset($data['ok']) && $data['ok'];
        $this->result = $data['result'] ?? null;

        $this->responseData = $data;
    }

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->ok;
    }

    /**
     * @return array|null
     */
    public function getResult(): ? array
    {
        return $this->result;
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
        $action = substr($method, 0, 3);
        if ($action === 'get') {
            $propertyName = strtolower(ltrim(preg_replace('/[A-Z]/', '_$0', substr($method, 3)), '_'));

            if (isset($this->responseData[$propertyName])) {
                return $this->responseData[$propertyName];
            }
        }

        throw new TelegramException('Undefined method: ' . $method);
    }
}
