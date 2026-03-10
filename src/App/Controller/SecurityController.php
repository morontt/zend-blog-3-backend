<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(private AuthenticationUtils $authUtils)
    {
    }

    /**
     * @Template()
     *
     * @return array<string, mixed>
     */
    #[Route(path: '/login')]
    public function loginAction(): array
    {
        $error = $this->authUtils->getLastAuthenticationError();
        $lastUsername = $this->authUtils->getLastUsername();

        return compact('error', 'lastUsername');
    }
}
