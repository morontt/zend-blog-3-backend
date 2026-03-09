<?php

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
    protected EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
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
