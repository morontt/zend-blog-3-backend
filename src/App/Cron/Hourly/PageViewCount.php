<?php

namespace App\Cron\Hourly;

use App\Cron\HourlyCronServiceInterface;
use App\Doctrine\DBAL\Type\MillisecondsDateTime;
use App\Entity\Post;
use App\Entity\SystemParameters;
use App\Entity\Tracking;
use App\Service\SystemParametersStorage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

class PageViewCount implements HourlyCronServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private SystemParametersStorage $paramStorage,
    ) {
    }

    public function run(): void
    {
        $updatesData = json_decode(
            $this->paramStorage->getParameter(SystemParameters::UPDATE_VIEW_COUNTS_DATA) ?? '{}',
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $from = $this->paramStorage->getParameter(SystemParameters::UPDATE_VIEW_COUNTS_FROM) ?? '2023-06-01 00:00:00';
        $now = (new DateTime())->format(MillisecondsDateTime::FORMAT_TIME);

        /** @var \App\Repository\TrackingRepository $trackingRepo */
        $trackingRepo = $this->em->getRepository(Tracking::class);
        $info = $trackingRepo->getViewCountsInfo($from, $now);

        $articleRepository = $this->em->getRepository(Post::class);
        $conn = $this->em->getConnection();

        $conn->beginTransaction();
        try {
            $countersData = [];
            foreach ($info as $item) {
                $articleRepository->increaseViewCounter($item['id'], (int)$item['cnt']);
                $countersData['ID' . $item['id']] = (int)$item['cnt'];
            }

            $countersData = $this->merge($countersData, $updatesData);
            $this->paramStorage->saveParameter(SystemParameters::UPDATE_VIEW_COUNTS_DATA, json_encode($countersData));

            $this->paramStorage->saveParameter(SystemParameters::UPDATE_VIEW_COUNTS_FROM, $now);
            $conn->commit();
        } catch (Throwable $e) {
            $conn->rollBack();

            throw $e;
        }
    }

    public function getMessage(): ?string
    {
        return null;
    }

    /**
     * @param array<string, int> $a
     * @param array<string, int> $b
     *
     * @return array<string, int>
     */
    public function merge(array $a, array $b): array
    {
        foreach ($b as $k => $v) {
            if (isset($a[$k])) {
                $a[$k] += $v;
            } else {
                $a[$k] = $v;
            }
        }

        return $a;
    }
}
