<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 18.10.2026
 * Time: 14:00
 */

namespace App\Worker\Task;

use App\DTO\Task\TrackingActivityDTO;
use App\Entity\GeoLocation;
use App\Entity\Post;
use App\Entity\Tracking;
use App\Entity\TrackingAgent;
use App\Repository\PostRepository;
use App\Repository\TrackingAgentRepository;
use App\Worker\BaseExecutor;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class TrackingActivity extends BaseExecutor
{
    private TrackingAgentRepository $agentRepo;
    private PostRepository $articleRepo;

    public function __construct(
        private EntityManagerInterface $em,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);

        $this->agentRepo = $em->getRepository(TrackingAgent::class);
        $this->articleRepo = $em->getRepository(Post::class);
    }

    public function getTaskName(): string
    {
        return 'tracking_activity';
    }

    /**
     * @param array<string, mixed> $data
     */
    public function handle(array $data): void
    {
        $dto = TrackingActivityDTO::fromArray($data);
        $trackingAgent = $this->getUserAgent($dto);

        $activity = new Tracking();
        $activity
            ->setTrackingAgent($trackingAgent)
            ->setCdn($dto->isCDN)
            ->setIpAddress($dto->addr)
            ->setTimeCreated($dto->time)
            ->setDuration((int)(0.5 + 0.001 * $dto->duration))
            ->setJs($dto->isJS)
            ->setStatusCode($dto->statusCode)
        ;

        $articleId = null;
        $matches = [];
        if (preg_match('/^\/(?:trk|article)\/(?P<slug>[^\/?#]+)/', $dto->requestedURI, $matches)) {
            $articleId = $this->articleRepo->getArticleIdBySlug($matches['slug']);
        }

        if ($articleId) {
            $activity->setPost($this->em->getReference(Post::class, $articleId));
        } else {
            $activity->setRequestURI($dto->requestedURI);
        }
        if ($dto->locationID) {
            $activity->setGeoLocation($this->em->getReference(GeoLocation::class, $dto->locationID));
        }
        if ($dto->method && $dto->method !== 'GET') {
            $activity->setMethod($dto->method);
        }

        $this->em->persist($activity);
        $this->em->flush();
    }

    private function getUserAgent(TrackingActivityDTO $dto): TrackingAgent
    {
        $agent = $this->agentRepo->findOneByHash(md5($dto->userAgent));
        if ($agent) {
            return $agent;
        }

        $agent = new TrackingAgent();
        $agent
            ->setUserAgent($dto->userAgent)
            ->setBot($dto->isBot)
        ;

        $this->em->persist($agent);
        $this->em->flush();

        return $agent;
    }
}
