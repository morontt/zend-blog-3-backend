<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 11.10.17
 * Time: 0:15
 */

namespace Xelbot\Telegram\Command;

use Xelbot\Telegram\TelegramRequester;

trait RequesterTrait
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
}
