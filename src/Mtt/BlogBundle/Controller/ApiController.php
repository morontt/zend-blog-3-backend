<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 16.11.14
 * Time: 17:43
 */

namespace Mtt\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 *
 * Class ApiController
 */
class ApiController extends BaseController
{
    /**
     * @Route("/")
     *
     * @return Response
     */
    public function infoAction()
    {
        return new Response('Hi :)');
    }
}
