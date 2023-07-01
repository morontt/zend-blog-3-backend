<?php

namespace Mtt\TestBundle\Service\IpInfo;

use Faker\Factory as FakerFactory;
use Mtt\BlogBundle\Service\IpInfo\IpInfoClientInterface;
use Mtt\BlogBundle\Service\IpInfo\LocationInfo;
use Symfony\Component\Intl\Intl;

class IpInfoDummyClient implements IpInfoClientInterface
{
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

        $faker = FakerFactory::create();
        $faker->seed(ip2long($ip));

        $countries = Intl::getRegionBundle()->getCountryNames('en_US');
        do {
            $countryCode = null;
            $country = $faker->country;
            foreach ($countries as $key => $value) {
                if ($country == $value) {
                    $countryCode = $key;
                    break;
                }
            }
        } while (is_null($countryCode));

        return LocationInfo::createFromArray([
            'countryCode' => $countryCode,
            'countryName' => $country,
            'regionName' => $faker->state,
            'cityName' => $faker->city,
            'zipCode' => $faker->postcode,
        ]);
    }
}
