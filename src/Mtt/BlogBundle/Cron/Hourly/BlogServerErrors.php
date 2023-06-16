<?php

namespace Mtt\BlogBundle\Cron\Hourly;

use Mtt\BlogBundle\Cron\HourlyCronServiceInterface;
use Mtt\BlogBundle\Entity\Repository\TrackingRepository;
use Mtt\BlogBundle\Entity\SystemParameters;
use Mtt\BlogBundle\Service\SystemParametersStorage;

class BlogServerErrors implements HourlyCronServiceInterface
{
    /**
     * @var TrackingRepository
     */
    private $repository;

    /**
     * @var SystemParametersStorage
     */
    private $paramStorage;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param TrackingRepository $repository
     * @param SystemParametersStorage $paramStorage
     */
    public function __construct(TrackingRepository $repository, SystemParametersStorage $paramStorage)
    {
        $this->repository = $repository;
        $this->paramStorage = $paramStorage;
    }

    public function run()
    {
        $from = $this->paramStorage->getParameter(SystemParameters::ERRORS_5XX_CHECK) ?? '2023-06-01 00:00:00';
        $now = date('Y-m-d H:i:s');

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
