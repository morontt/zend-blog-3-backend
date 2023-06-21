<?php

namespace Mtt\BlogBundle\Cron\Daily;

use Doctrine\ORM\EntityManagerInterface;
use Mtt\BlogBundle\Cron\DailyCronServiceInterface;
use Mtt\BlogBundle\Doctrine\DBAL\Type\MillisecondsDateTime;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\SystemParameters;
use Mtt\BlogBundle\Entity\Tracking;
use Mtt\BlogBundle\Service\SystemParametersStorage;

class PageViewCount implements DailyCronServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SystemParametersStorage
     */
    private $paramStorage;

    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $views = 0;

    public function __construct(EntityManagerInterface $em, SystemParametersStorage $paramStorage)
    {
        $this->em = $em;
        $this->paramStorage = $paramStorage;
    }

    public function run()
    {
        $from = $this->paramStorage->getParameter(SystemParameters::UPDATE_VIEW_COUNTS_FROM) ?? '2023-06-01 00:00:00';
        $now = (new \DateTime())->format(MillisecondsDateTime::FORMAT_TIME);

        $trackingRepo = $this->em->getRepository(Tracking::class);
        $info = $trackingRepo->getViewCountsInfo($from, $now);
        $this->count = count($info);

        $articleRepository = $this->em->getRepository(Post::class);
        $conn = $this->em->getConnection();

        $conn->beginTransaction();
        try {
            foreach ($info as $item) {
                $this->views += (int)$item['cnt'];
                $articleRepository->increaseViewCounter($item['id'], (int)$item['cnt']);
            }

            $this->paramStorage->saveParameter(SystemParameters::UPDATE_VIEW_COUNTS_FROM, $now);
            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();

            throw $e;
        }
    }

    public function getMessage(): ?string
    {
        return sprintf('%d articles was updated, %d views saved', $this->count, $this->views);
    }
}
