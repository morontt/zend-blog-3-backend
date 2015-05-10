<?php

namespace Mtt\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DefaultController
 * @package Mtt\BlogBundle\Controller
 */
class DefaultController extends BaseController
{
    /**
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/ajax/category-list", options={"expose"=true})
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
