<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(private AuthenticationUtils $authUtils)
    {
    }

    #[Route(path: '/admin/login', name: 'app_login')]
    public function loginAction(): Response
    {
        $error = $this->authUtils->getLastAuthenticationError();
        $lastUsername = $this->authUtils->getLastUsername();

        return $this->render('security/login.html.twig', compact('error', 'lastUsername'));
    }
}
