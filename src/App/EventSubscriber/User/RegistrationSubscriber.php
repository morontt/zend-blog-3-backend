<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 22.04.2025
 * Time: 10:03
 */

namespace App\EventSubscriber\User;

use App\Event\UserExtraEvent;
use App\Service\Mailer;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Xelbot\Telegram\Robot;

class RegistrationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Robot $bot,
        private Mailer $mailer,
        private LoggerInterface $logger,
    ) {
    }

    public function newRegistration(UserExtraEvent $event): void
    {
        $info = $event->getExtraInfo();
        if (!$event->isNewUser()) {
            $this->logger->info('User was already registered', [
                'user_id' => $info->getUser()->getId(),
            ]);

            return;
        }

        $message = 'Новая регистрация: ';
        $message .= $info->getDisplayName();
        $message .= ', ' . $info->getDataProvider();

        $this->bot->sendMessage($message);
        $this->mailer->systemNotification([$message], true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserExtraEvent::class => ['newRegistration', 10],
        ];
    }
}
