<?php

namespace Mtt\BlogBundle\Controller;

use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Repository\ViewCommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 */
class DefaultController extends AbstractController
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
     * @param ViewCommentRepository $repository
     *
     * @return array
     */
    public function previewAction(Post $post, ViewCommentRepository $repository)
    {
        $comments = $repository->getCommentsByPost($post);

        return compact('post', 'comments');
    }
}
