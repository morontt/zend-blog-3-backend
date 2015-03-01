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
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @return JsonResponse
     */
    public function findAllAction(Request $request)
    {
        $pagination = $this->paginate(
            $this->getPostRepository()->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getPostsArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }
}
