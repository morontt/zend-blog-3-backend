<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\ViewCommentRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct(private string $kernelEnv, private string $cdnUrl, private string $frontendSite)
    {
    }

    /**
     * @throws JsonException
     */
    #[Route(path: '/')]
    public function indexAction(): Response
    {
        $isDev = $this->kernelEnv === 'dev';

        $environment = [
            'modulePrefix' => 'mtt-blog',
            'environment' => ($this->kernelEnv === 'prod') ? 'production' : 'development',
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

        return $this->render('default/index.html.twig', [
            'env' => urlencode(json_encode($environment, JSON_THROW_ON_ERROR)),
        ]);
    }

    #[Route(path: '/preview/{slug}', name: 'post_preview', options: ['expose' => true])]
    public function previewAction(ViewCommentRepository $repository, ?Post $post = null): Response
    {
        if (!$post) {
            throw $this->createNotFoundException();
        }

        $comments = $repository->getCommentsByPost($post);

        return $this->render('default/preview.html.twig', compact('post', 'comments'));
    }

    /**
     * @return JsonResponse
     */
    #[Route(path: '/purge-cache', name: 'purge_cache', options: ['expose' => true], methods: ['POST'])]
    public function purgeBlogCacheAction(): JsonResponse
    {
        $httpClient = new Client(['base_uri' => $this->frontendSite]);
        try {
            $response = $httpClient->request(
                'POST',
                '/purge-cache',
                [
                    'headers' => ['X-Ban-Token' => getenv('VARNISH_BAN_TOKEN')],
                ]
            );
            $status = $response->getStatusCode();
        } catch (GuzzleException $e) {
            $status = Response::HTTP_BAD_REQUEST;
        }

        return new JsonResponse([], $status);
    }
}
