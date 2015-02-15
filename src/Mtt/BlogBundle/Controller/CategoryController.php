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
     * @return JsonResponse
     */
    public function findAllAction()
    {
        $result = $this->getDataConverter()
            ->getCategoryArray($this->getCategoryRepository()->findAll());

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
}
