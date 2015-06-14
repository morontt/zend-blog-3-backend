<?php

namespace Mtt\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/login")
     * @Template()
     *
     * @return array
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return compact('error', 'lastUsername');
    }

    /**
     * @Route("/login_check")
     */
    public function loginCheckAction()
    {
        return null;
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction()
    {
        return null;
    }
}
