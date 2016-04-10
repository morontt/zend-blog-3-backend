<?php

namespace Mtt\BlogBundle\Controller;

use Mtt\BlogBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @Route("/preview/{slug}", name="post_preview", options={"expose"=true})
     * @ParamConverter("post", options={"mapping": {"slug": "url"}})
     * @Template()
     *
     * @param Post $post
     * @return array
     */
    public function previewAction(Post $post)
    {
        return compact('post');
    }
}
