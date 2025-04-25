<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private AuthenticationUtils $authUtils;

    public function __construct(AuthenticationUtils $authUtils)
    {
        $this->authUtils = $authUtils;
    }

    /**
     * @Route("/login")
     *
     * @Template()
     *
     * @return array
     */
    public function loginAction(): array
    {
        $error = $this->authUtils->getLastAuthenticationError();
        $lastUsername = $this->authUtils->getLastUsername();

        return compact('error', 'lastUsername');
    }
}
