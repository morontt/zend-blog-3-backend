<?php

namespace App\OAuth2;

use App\OAuth2\Providers\DataProviderInterface;
use App\OAuth2\Providers\VkDataProvider;
use App\OAuth2\Providers\YandexDataProvider;
use InvalidArgumentException;

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

        throw new InvalidArgumentException('Unknown provider: ' . $providerName);
    }
}
