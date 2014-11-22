<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 16.11.14
 * Time: 17:43
 */

namespace Mtt\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api")
 *
 * Class ApiController
 * @package Mtt\BlogBundle\Controller
 */
class ApiController extends BaseController
{
    /**
     * @Route("/")
     *
     * @return array
     */
    public function infoAction()
    {
        return [];
    }

    /**
     * @Route("/categories")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function categoryFindAllAction()
    {
        $result = $this->getDataConverter()
            ->getCategoryArray($this->getCategoryRepository()->findAll());

        return new JsonResponse($result);
    }

    /**
     * @Route("/tags")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function tagFindAllAction()
    {
        $result = $this->getDataConverter()
            ->getTagsArray($this->getTagRepository()->findAll());

        return new JsonResponse($result);
    }
}
