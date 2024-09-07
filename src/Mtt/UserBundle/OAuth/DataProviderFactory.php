<?php

namespace Mtt\UserBundle\OAuth;

use Mtt\UserBundle\OAuth\Providers\DataProviderInterface;
use Mtt\UserBundle\OAuth\Providers\VkDataProvider;
use Mtt\UserBundle\OAuth\Providers\YandexDataProvider;

class DataProviderFactory
{
    public function dataProvider(string $providerName): DataProviderInterface
    {
        switch ($providerName) {
            case DataProviderInterface::YANDEX:
                return new YandexDataProvider();
            case DataProviderInterface::VK:
                return new VkDataProvider();
        }

        throw new \InvalidArgumentException('Unknown provider: ' . $providerName);
    }
}
