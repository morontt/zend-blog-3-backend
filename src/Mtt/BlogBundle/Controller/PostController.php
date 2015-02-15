<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 19:30
 */

namespace Mtt\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/posts")
 *
 * Class PostController
 * @package Mtt\BlogBundle\Controller
 */
class PostController extends BaseController
{
    /**
     * @Route("")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function findAllAction()
    {
        $result = $this->getDataConverter()
            ->getPostsArray($this->getPostRepository()->findAll());

        return new JsonResponse($result);
    }
}
