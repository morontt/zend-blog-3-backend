<?php

namespace App\Telegram\Command;

use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Command\TelegramCommandTrait;
use Xelbot\Telegram\Entity\Message;
use Xelbot\Telegram\Robot;

class Start implements TelegramCommandInterface
{
    use TelegramCommandTrait;

    /**
     * @param Message $message
     */
    public function execute(Message $message): void
    {
        $text = 'Приветствую тебя, человек ' . Robot::EMOJI_ROBOT;
        $text .= "\n\nЯ служу своему создателю и выполняю некоторые поручения по его блогу.";
        $text .= ' Не знаю, чем могу быть тебе полезен, но можешь писать сюда что угодно.';
        $text .= ' Возможно, получишь какой-нибудь ответ, поскольку пишешь не совсем в космос ';
        $text .= '&#x2728;';

        // TODO Null pointer exception may occur here
        $this->requester->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
