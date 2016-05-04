<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 21.07.15
 * Time: 23:40
 */

namespace Mtt\UserBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Mtt\BlogBundle\Utils\Http;
use Mtt\UserBundle\Entity\User;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    /**
     * @var ObjectManager
     */
    protected $em;

    /**
     * @param ObjectManager $em
     */
    function __construct(ObjectManager $em)
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
                ->setLastLogin(new \DateTime())
                ->setLoginCount($user->getLoginCount() + 1)
                ->setIpAddressLast(Http::getClientIp())
            ;

            $this->em->persist($user);
            $this->em->flush();
        }
    }
}
