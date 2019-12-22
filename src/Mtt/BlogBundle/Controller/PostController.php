<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 19:30
 */

namespace Mtt\BlogBundle\Controller;

use Doctrine\ORM\ORMException;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Repository\PostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/posts")
 *
 * Class PostController
 */
class PostController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param PostRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, PostRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getPostArray($pagination, 'category');

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param Post $entity
     *
     * @return JsonResponse
     */
    public function findAction(Post $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getPost($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @param Request $request
     *
     * @throws ORMException
     *
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $result = $this->getDataConverter()
            ->savePost(new Post(), $request->request->get('post'));

        return new JsonResponse($result, 201);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param Post $entity
     *
     * @throws ORMException
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, Post $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->savePost($entity, $request->request->get('post'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
     *
     * @param Post $entity
     *
     * @throws ORMException
     *
     * @return JsonResponse
     */
    public function deleteAction(Post $entity): JsonResponse
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }
}
