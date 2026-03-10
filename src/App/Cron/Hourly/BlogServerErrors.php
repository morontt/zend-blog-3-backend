<?php

namespace App\Cron\Hourly;

use App\Cron\HourlyCronServiceInterface;
use App\Doctrine\DBAL\Type\MillisecondsDateTime;
use App\Entity\SystemParameters;
use App\Repository\TrackingRepository;
use App\Service\SystemParametersStorage;
use DateTime;

class BlogServerErrors implements HourlyCronServiceInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $errors = [];

    public function __construct(
        private TrackingRepository $repository,
        private SystemParametersStorage $paramStorage,
    ) {
    }

    public function run(): void
    {
        $from = $this->paramStorage->getParameter(SystemParameters::ERRORS_5XX_CHECK) ?? '2023-06-01 00:00:00';
        $now = (new DateTime())->format(MillisecondsDateTime::FORMAT_TIME);

        $this->errors = $this->repository->getDataAboutServerErrors($from, $now);
        $this->paramStorage->saveParameter(SystemParameters::ERRORS_5XX_CHECK, $now);
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        if (count($this->errors)) {
            $message = '';
            foreach ($this->errors as $error) {
                $message .= sprintf("\n%d %s", $error['cnt'], $error['requestURI'] ?: 'articleID: ' . $error['postID']);
            }

            return $message;
        }

        return null;
    }
}
