<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 08.04.26
 * Time: 08:44
 */

namespace App\EventSubscriber\Security;

use App\Security\SecureCookie\Cookie;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class SecureCookieLogoutSubscriber implements EventSubscriberInterface
{
    public function onLogout(LogoutEvent $event): void
    {
        if (
            $event->getRequest()->cookies->has(Cookie::SESSION_KEY)
            && $response = $event->getResponse()
        ) {
            $response->headers->clearCookie(Cookie::SESSION_KEY);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogout',
        ];
    }
}
