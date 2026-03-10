<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 20:03
 */

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Repository\ViewCategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/categories')]
class CategoryController extends BaseController
{
    /**
     * @param Request $request
     * @param ViewCategoryRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, ViewCategoryRepository $repository): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getCategoryArray($repository->getListQuery()->getResult());

        return new JsonResponse($result);
    }

    /**
     * @param Category $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(Category $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getCategory($entity);

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['POST'])]
    public function createAction(Request $request): JsonResponse
    {
        $form = $this->createObjectForm('category', CategoryFormType::class);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()->saveCategory(new Category(), $formData['category']);

        return new JsonResponse($result, Response::HTTP_CREATED);
    }

    /**
     * TODO update nested-set tree
     *
     * @param Request $request
     * @param Category $entity
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateAction(Request $request, Category $entity): JsonResponse
    {
        $form = $this->createObjectForm('category', CategoryFormType::class, true);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()->saveCategory($entity, $formData['category']);

        return new JsonResponse($result);
    }

    /**
     * TODO update nested-set tree
     *
     * @param Category $entity
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteAction(Category $entity): JsonResponse
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }

    /**
     * @param CategoryRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '/list', name: 'category_choices', options: ['expose' => true], methods: ['GET'])]
    public function ajaxCategoryListAction(CategoryRepository $repository): JsonResponse
    {
        return new JsonResponse($repository->getNamesArray());
    }
}
