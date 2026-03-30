<?php

declare(strict_types=1);

namespace App\Service\IpInfo;

use App\LogTrait;
use Faker\Factory as FakerFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\Intl\Countries;

#[When(env: 'test')]
class IpInfoDummyClient implements IpInfoClientInterface
{
    use LogTrait;

    public function __construct(
        LoggerInterface $logger,
    ) {
        $this->setLogger($logger);
    }

    /**
     * @param string $ip
     *
     * @return LocationInfo|null
     */
    public function getLocationInfo(string $ip): ?LocationInfo
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return null;
        }

        $this->info(
            'Request location by IP address',
            ['ip' => $ip]
        );

        $startTime = microtime(true);

        $faker = FakerFactory::create();
        $faker->seed(ip2long($ip));

        $countries = Countries::getNames('en_US');
        do {
            $countryCode = null;
            $country = $faker->country;
            foreach ($countries as $cntrCode => $cntrName) {
                if ($country == $cntrName) {
                    $countryCode = $cntrCode;
                    break;
                }
            }
        } while (is_null($countryCode));

        $data = [
            'countryCode' => $countryCode,
            'countryName' => $country,
            'regionName' => $faker->state,
            'cityName' => $faker->city,
            'zipCode' => $faker->postcode,
        ];

        $endTime = microtime(true);

        $this->info('Generate Location', [
            'data' => $data,
            'duration' => round($endTime - $startTime, 5),
        ]);

        return LocationInfo::createFromArray($data);
    }

    public function isLimitedRequests(): bool
    {
        return false;
    }
}
