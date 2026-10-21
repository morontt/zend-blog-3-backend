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
use App\Repository\TrackingAgentRepository;
use App\Worker\BaseExecutor;
use Doctrine\ORM\EntityManagerInterface;
use GearmanJob;
use Psr\Log\LoggerInterface;

class TrackingActivity extends BaseExecutor
{
    private TrackingAgentRepository $agentRepo;

    public function __construct(
        private EntityManagerInterface $em,
        LoggerInterface $logger,
    ) {
        parent::__construct($logger);

        $this->agentRepo = $em->getRepository(TrackingAgent::class);
    }

    public function execute(GearmanJob $job): void
    {
        $data = $this->workload($job);
        if (!$data) {
            $job->sendFail();

            return;
        }

        $this->debug('tracking data', $data);

        $this->saveActivity(TrackingActivityDTO::fromArray($data));

        $job->sendComplete('');
    }

    public function getTaskName(): string
    {
        return 'tracking_activity';
    }

    private function saveActivity(TrackingActivityDTO $dto): void
    {
        $trackingAgent = $this->getUserAgent($dto);

        $activity = new Tracking();
        $activity
            ->setTrackingAgent($trackingAgent)
            ->setCdn($dto->isCDN)
            ->setIpAddress($dto->addr)
            ->setTimeCreated($dto->time)
            ->setDuration((int)(0.001 * $dto->duration))
            ->setJs($dto->isJS)
            ->setStatusCode($dto->statusCode)
        ;

        if ($dto->articleId) {
            $activity->setPost($this->em->getReference(Post::class, $dto->articleId));
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
