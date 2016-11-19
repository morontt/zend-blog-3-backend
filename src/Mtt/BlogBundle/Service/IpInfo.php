<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 13:05
 */

namespace Mtt\BlogBundle\Service;

use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Entity\GeoLocation;
use Mtt\BlogBundle\Entity\GeoLocationCity;
use Mtt\BlogBundle\Entity\GeoLocationCountry;

class IpInfo
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     * @param string $key
     */
    public function __construct(EntityManager $em, $key)
    {
        $this->em = $em;
        $this->key = $key;
    }

    /**
     * @param string $ip
     *
     * @return GeoLocation|null
     */
    public function getLocationByIp($ip)
    {
        $location = $this->em->getRepository('MttBlogBundle:GeoLocation')->findOneByIpAddress($ip);
        if (!$location) {
            $data = $this->getCityInfo($ip);
            if ($data) {
                $city = $this->getCity($data);
                if ($city) {
                    $location = new GeoLocation();
                    $location
                        ->setCity($city)
                        ->setIpAddress($ip)
                    ;

                    $this->em->persist($location);
                    $this->em->flush();
                }
            }
        }

        return $location;
    }

    /**
     * @param array $data
     *
     * @return GeoLocationCity|null
     */
    protected function getCity(array $data)
    {
        $city = null;
        if (!empty($data['cityName']) && !empty($data['regionName'])) {
            $country = $this->getCountry($data);
            if ($country) {
                $city = $this->em->getRepository('MttBlogBundle:GeoLocationCity')->findOneBy([
                    'city' => $data['cityName'],
                    'region' => $data['regionName'],
                    'country' => $country->getId(),
                ]);

                if (!$city) {
                    $city = new GeoLocationCity();
                    $city
                        ->setCity($data['cityName'])
                        ->setRegion($data['regionName'])
                        ->setLatitude($data['latitude'])
                        ->setLongitude($data['longitude'])
                        ->setTimeZone($data['timeZone'])
                        ->setZip($data['zipCode'])
                        ->setCountry($country)
                    ;

                    $this->em->persist($city);
                    $this->em->flush();
                }
            }
        }

        return $city;
    }

    /**
     * @param array $data
     *
     * @return GeoLocationCountry|null
     */
    protected function getCountry(array $data)
    {
        $country = null;
        if (!empty($data['countryCode']) && !empty($data['countryName'])) {
            $country = $this->em
                ->getRepository('MttBlogBundle:GeoLocationCountry')
                ->findOneByCode($data['countryCode']);

            if (!$country) {
                $country = new GeoLocationCountry();
                $country
                    ->setCode($data['countryCode'])
                    ->setName($data['countryName'])
                ;

                $this->em->persist($country);
                $this->em->flush();
            }
        }

        return $country;
    }

    /**
     * @param string $ip
     *
     * @return array|null
     */
    protected function getCityInfo($ip)
    {
        $result = null;

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $params = http_build_query([
                'key' => $this->key,
                'ip' => $ip,
                'format' => 'json',
            ]);

            $context = stream_context_create([
                'http' => [
                    'timeout' => 4,
                ],
            ]);
            $json = @file_get_contents('http://api.ipinfodb.com/v3/ip-city/?' . $params, false, $context);
            $result = json_decode($json, true);
        }

        return $result;
    }
}
