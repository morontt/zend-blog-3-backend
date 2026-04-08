<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 21.07.15
 * Time: 23:40
 */

namespace App\EventListener\User;

use App\Entity\LoginHistory;
use App\Entity\User;
use App\Utils\Http;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function onLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof User) {
            $history = new LoginHistory();
            $history
                ->setUser($user)
                ->setIpAddress(Http::getClientIp())
            ;

            $this->em->persist($history);
            $this->em->flush();
        }
    }
}
