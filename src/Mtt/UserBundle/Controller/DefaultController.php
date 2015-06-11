<?php

namespace Mtt\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/hello")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return [];
    }
}
