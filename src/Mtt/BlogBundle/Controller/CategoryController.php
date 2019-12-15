<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 20:03
 */

namespace Mtt\BlogBundle\Controller;

use Doctrine\ORM\ORMException;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Entity\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $pagination = $this->paginate(
            $repository->getListQuery(true),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getCategoryArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

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
     * @throws ORMException
     *
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $result = $this->getDataConverter()
            ->saveCategory(new Category(), $request->request->get('category'));

        return new JsonResponse($result, 201);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param Category $entity
     *
     * @throws ORMException
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, Category $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->saveCategory($entity, $request->request->get('category'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
     *
     * @param Category $entity
     *
     * @throws ORMException
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
    public function ajaxCategoryAction(CategoryRepository $repository): JsonResponse
    {
        $categories = $repository->getNamesArray();

        return new JsonResponse($categories);
    }
}
