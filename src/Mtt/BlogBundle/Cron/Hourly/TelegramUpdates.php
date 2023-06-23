<?php

namespace Mtt\BlogBundle\Cron\Hourly;

use Mtt\BlogBundle\Cron\HourlyCronServiceInterface;
use Mtt\BlogBundle\Doctrine\DBAL\Type\MillisecondsDateTime;
use Mtt\BlogBundle\Entity\Repository\TelegramUpdateRepository;
use Mtt\BlogBundle\Entity\SystemParameters;
use Mtt\BlogBundle\Service\SystemParametersStorage;

class TelegramUpdates implements HourlyCronServiceInterface
{
    private TelegramUpdateRepository $repository;

    private SystemParametersStorage $paramStorage;

    private int $adminId;

    private int $cnt = 0;

    public function __construct(
        TelegramUpdateRepository $repository,
        SystemParametersStorage $paramStorage,
        int $adminId
    ) {
        $this->repository = $repository;
        $this->paramStorage = $paramStorage;
        $this->adminId = $adminId;
    }

    public function run()
    {
        $from = $this->paramStorage->getParameter(SystemParameters::TELEGRAM_UPDATES_CHECK) ?? '2023-06-23 16:00:00';
        $now = (new \DateTime())->format(MillisecondsDateTime::FORMAT_TIME);

        $this->cnt = $this->repository->countNewMessages($from, $now, $this->adminId);
        $this->paramStorage->saveParameter(SystemParameters::TELEGRAM_UPDATES_CHECK, $now);
    }

    public function getMessage(): ?string
    {
        if ($this->cnt > 0) {
            return 'Кто-то общался с ботом. Новых сообщений: ' . $this->cnt;
        }

        return null;
    }
}
