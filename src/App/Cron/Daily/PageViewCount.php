<?php

namespace App\Cron\Daily;

use App\Cron\DailyCronServiceInterface;
use App\Entity\SystemParameters;
use App\Service\SystemParametersStorage;

class PageViewCount implements DailyCronServiceInterface
{
    /**
     * @var SystemParametersStorage
     */
    private SystemParametersStorage $paramStorage;

    /**
     * @var int
     */
    private int $count = 0;

    /**
     * @var int
     */
    private int $views = 0;

    public function __construct(SystemParametersStorage $paramStorage)
    {
        $this->paramStorage = $paramStorage;
    }

    public function run(): void
    {
        $updatesData = json_decode(
            $this->paramStorage->getParameter(SystemParameters::UPDATE_VIEW_COUNTS_DATA) ?? '{}',
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->count = count($updatesData);
        foreach ($updatesData as $item) {
            $this->views += $item;
        }

        $this->paramStorage->saveParameter(SystemParameters::UPDATE_VIEW_COUNTS_DATA, '{}');
    }

    public function getMessage(): ?string
    {
        return sprintf('%d articles was updated, %d views saved', $this->count, $this->views);
    }
}
