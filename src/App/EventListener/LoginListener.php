<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 21.07.15
 * Time: 23:40
 */

namespace App\EventListener;

use App\Entity\User;
use App\Utils\Http;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

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
    public function onLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof User) {
            $user
                ->setLastLogin(new DateTime())
                ->setLoginCount($user->getLoginCount() + 1)
                ->setIpAddressLast(Http::getClientIp())
            ;

            $this->em->persist($user);
            $this->em->flush();
        }
    }
}
