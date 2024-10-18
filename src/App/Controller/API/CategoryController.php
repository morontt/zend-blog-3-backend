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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/categories")
 *
 * Class CategoryController
 */
class CategoryController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param CategoryRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, CategoryRepository $repository): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getCategoryArray($repository->getListQuery()->getResult());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param Category $entity
     *
     * @return JsonResponse
     */
    public function findAction(Category $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getCategory($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @param Request $request
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
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
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param Category $entity
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
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
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
     *
     * @param Category $entity
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return JsonResponse
     */
    public function deleteAction(Category $entity): JsonResponse
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }

    /**
     * @Route("/list", name="category_choices", options={"expose"=true}, methods={"GET"})
     *
     * @param CategoryRepository $repository
     *
     * @return JsonResponse
     */
    public function ajaxCategoryListAction(CategoryRepository $repository): JsonResponse
    {
        return new JsonResponse($repository->getNamesArray());
    }
}
