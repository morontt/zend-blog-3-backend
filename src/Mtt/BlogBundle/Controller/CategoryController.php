<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 20:03
 */

namespace Mtt\BlogBundle\Controller;

use Mtt\BlogBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/categories")
 *
 * Class CategoryController
 * @package Mtt\BlogBundle\Controller
 */
class CategoryController extends BaseController
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
        if ($request->query->get('scope') == 'all') {
            $result = $this->getDataConverter()
                ->getCategoryArray(
                    $this->getCategoryRepository()->getListQuery()->getResult()
                );
        } else {
            $pagination = $this->paginate(
                $this->getCategoryRepository()->getListQuery(),
                $request->query->get('page', 1)
            );

            $result = $this->getDataConverter()
                ->getCategoryArray($pagination);

            $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param Category $entity
     * @return JsonResponse
     */
    public function findAction(Category $entity)
    {
        $result = $this->getDataConverter()
            ->getCategory($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("PUT")
     *
     * @param Request $request
     * @param Category $entity
     * @return JsonResponse
     */
    public function updateAction(Request $request, Category $entity)
    {
        $result = $this->getDataConverter()
            ->saveCategory($entity, $request->request->get('category'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("DELETE")
     *
     * @param Category $entity
     * @return JsonResponse
     */
    public function deleteAction(Category $entity)
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }

    /**
     * @Route("")
     * @Method("POST")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createCategoryAction(Request $request)
    {
        $result = $this->getDataConverter()
            ->createCategory($request->request->get('category'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/list", name="category_choices", options={"expose"=true})
     *
     * @return JsonResponse
     */
    public function ajaxCategoryAction()
    {
        $categories = $this->getCategoryRepository()
            ->getNamesArray();

        return new JsonResponse($categories);
    }
}
