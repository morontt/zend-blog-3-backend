<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 20:03
 */

namespace Mtt\BlogBundle\Controller;

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
        $pagination = $this->paginate(
            $this->getCategoryRepository()->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getCategoryArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param $id
     * @return JsonResponse
     */
    public function findAction($id)
    {
        /**
         * @var \Mtt\BlogBundle\Entity\Category $entity
         */
        $entity = $this->getCategoryRepository()->find((int)$id);

        $result = $this->getDataConverter()
            ->getCategory($entity);

        return new JsonResponse($result);
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
}
