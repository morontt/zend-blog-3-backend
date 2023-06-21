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
     * @var string
     */
    private $kernelEnv;

    /**
     * @var string
     */
    private $cdnUrl;

    public function __construct(string $kernelEnv, string $cdnUrl)
    {
        $this->kernelEnv = $kernelEnv;
        $this->cdnUrl = $cdnUrl;
    }

    /**
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $isDev = $this->kernelEnv == 'dev';

        $environment = [
            'modulePrefix' => 'mtt-blog',
            'environment' => ($this->kernelEnv == 'prod') ? 'production' : 'development',
            'baseURL' => '/',
            'locationType' => 'hash',
            'EmberENV' => [
                'FEATURES' => [],
            ],
            'APP' => [
                'LOG_ACTIVE_GENERATION' => $isDev,
                'LOG_TRANSITIONS' => $isDev,
                'LOG_TRANSITIONS_INTERNAL' => $isDev,
                'LOG_VIEW_LOOKUPS' => $isDev,
                'LOG_BINDINGS' => $isDev,
                'name' => 'mtt-blog',
                'version' => '0.0.1 a8c1ef27',
            ],
            'contentSecurityPolicyHeader' => 'Content-Security-Policy-Report-Only',
            'contentSecurityPolicy' => [
                'default-src' => "'none'",
                'script-src' => "'self' 'unsafe-eval'",
                'font-src' => "'self'",
                'connect-src' => "'self'",
                'img-src' => "'self'",
                'style-src' => "'self'",
                'media-src' => "'self'",
            ],
            'exportApplicationGlobal' => true,
            'appParameters' => [
                'apiURL' => $this->generateUrl('api_root'),
                'cdnURL' => $this->cdnUrl,
            ],
        ];

        return [
            'env' => urlencode(json_encode($environment)),
        ];
    }

    /**
     * @Route("/preview/{slug}", name="post_preview", options={"expose"=true})
     * @ParamConverter("post", options={"mapping": {"slug": "url"}})
     * @Template()
     *
     * @param Post|null $post
     * @param ViewCommentRepository $repository
     *
     * @return array
     */
    public function previewAction(ViewCommentRepository $repository, Post $post = null): array
    {
        if (!$post) {
            throw $this->createNotFoundException();
        }

        $comments = $repository->getCommentsByPost($post);

        return compact('post', 'comments');
    }
}
