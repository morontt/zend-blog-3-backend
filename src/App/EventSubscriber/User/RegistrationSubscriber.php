<?php

/**
 * User: morontt
 * Date: 22.04.2025
 * Time: 10:03
 */

namespace App\EventSubscriber\User;

use App\Event\UserExtraEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Xelbot\Telegram\Robot;

class RegistrationSubscriber implements EventSubscriberInterface
{
    public function __construct(private Robot $bot)
    {
    }

    public function newRegistration(UserExtraEvent $event): void
    {
        $info = $event->getExtraInfo();

        $message = 'Новая регистрация: ';
        $message .= $info->getDisplayName();
        $message .= ', ' . $info->getDataProvider();

        $this->bot->sendMessage($message);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserExtraEvent::class => ['newRegistration', 10],
        ];
    }
}
