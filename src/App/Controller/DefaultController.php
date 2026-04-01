<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\ViewCommentRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private string $kernelEnv,
        private string $cdnUrl,
        private string $frontendSite,
    ) {
    }

    #[Route(path: '/')]
    public function indexAction(): Response
    {
        return new Response('Welcome', headers: ['Content-Type' => 'text/plain']);
    }

    #[Route(path: '/admin')]
    public function dashboardAction(): Response
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
                'blogURL' => $this->frontendSite,
                'build_info' => [
                    'build_version_php' => $_ENV['BUILD_VERSION_PHP'] ?? '-',
                    'build_time_php' => (int)($_ENV['BUILD_TIME_PHP'] ?? 0),
                    'build_version_js' => $_ENV['BUILD_VERSION_JS'] ?? '-',
                    'build_time_js' => (int)($_ENV['BUILD_TIME_JS'] ?? 0),
                ],
            ],
        ];

        return $this->render('default/index.html.twig', [
            'env' => urlencode(json_encode($environment, JSON_THROW_ON_ERROR)),
        ]);
    }

    #[Route(path: '/admin/preview/{slug}', name: 'post_preview', options: ['expose' => true])]
    public function previewAction(
        ViewCommentRepository $repository,
        #[MapEntity(mapping: ['slug' => 'url'])]
        Post $post,
    ): Response {
        $comments = $repository->getCommentsByPost($post);

        return $this->render('default/preview.html.twig', compact('post', 'comments'));
    }

    #[Route(path: '/admin/purge-cache', name: 'purge_cache', options: ['expose' => true], methods: ['POST'])]
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
