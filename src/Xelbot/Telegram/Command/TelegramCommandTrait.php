<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 11.10.17
 * Time: 0:15
 */

namespace Xelbot\Telegram\Command;

use Xelbot\Telegram\TelegramRequester;

trait TelegramCommandTrait
{
    /**
     * @var TelegramRequester
     */
    protected $requester;

    /**
     * @param TelegramRequester $requester
     */
    public function setRequester(TelegramRequester $requester): void
    {
        $this->requester = $requester;
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        $classNameArr = explode('\\', __CLASS__);
        $name = preg_replace_callback(
            '/(?<=[a-z])[A-Z]/',
            function ($el) {
                return '-' . strtolower($el[0]);
            },
            $classNameArr[array_key_last($classNameArr)]
        );

        return strtolower($name);
    }
}
