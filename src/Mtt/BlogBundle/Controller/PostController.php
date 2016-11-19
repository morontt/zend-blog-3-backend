<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 19:30
 */

namespace Mtt\BlogBundle\Controller;

use Mtt\BlogBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/posts")
 *
 * Class PostController
 */
class PostController extends BaseController
{
    /**
     * @Route("")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request)
    {
        $pagination = $this->paginate(
            $this->getPostRepository()->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getPostArray($pagination, 'category');

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param Post $entity
     *
     * @return JsonResponse
     */
    public function findAction(Post $entity)
    {
        $result = $this->getDataConverter()
            ->getPost($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $result = $this->getDataConverter()
            ->savePost(new Post(), $request->request->get('post'));

        return new JsonResponse($result, 201);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("PUT")
     *
     * @param Request $request
     * @param Post $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, Post $entity)
    {
        $result = $this->getDataConverter()
            ->savePost($entity, $request->request->get('post'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("DELETE")
     *
     * @param Post $entity
     *
     * @return JsonResponse
     */
    public function deleteAction(Post $entity)
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }
}
