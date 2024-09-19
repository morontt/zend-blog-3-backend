<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 19:30
 */

namespace App\Controller\API;

use Doctrine\ORM\ORMException;
use App\Controller\BaseController;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Form\ArticleFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $form = $this->createObjectForm('post', ArticleFormType::class);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()->savePost(new Post(), $formData['post']);

        return new JsonResponse($result, Response::HTTP_CREATED);
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
        $form = $this->createObjectForm('post', ArticleFormType::class, true);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()->savePost($entity, $formData['post']);

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
