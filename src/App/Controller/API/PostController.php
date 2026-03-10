<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 19:30
 */

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Entity\Post;
use App\Form\ArticleFormType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/posts')]
class PostController extends BaseController
{
    /**
     * @param Request $request
     * @param PostRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, PostRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getPostArray($pagination, 'category');

        return new JsonResponse($result);
    }

    /**
     * @param Post $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(Post $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getPost($entity);

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['POST'])]
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
     * @param Request $request
     * @param Post $entity
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
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
     * @param Post $entity
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteAction(Post $entity): JsonResponse
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }
}
