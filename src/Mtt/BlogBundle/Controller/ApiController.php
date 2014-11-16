<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 16.11.14
 * Time: 17:43
 */

namespace Mtt\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/api")
 *
 * Class ApiController
 * @package Mtt\BlogBundle\Controller
 */
class ApiController extends BaseController
{
    /**
     * @Route("/")
     *
     * @return array
     */
    public function infoAction()
    {
        return [];
    }
}
