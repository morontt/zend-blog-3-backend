<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 13:05
 */

namespace Mtt\BlogBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param EntityManagerInterface $em
     * @param string $key
     */
    public function __construct(EntityManagerInterface $em, string $key)
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
        $location = $this->em->getRepository('MttBlogBundle:GeoLocation')->findOrCreateByIpAddress($ip);
        if ($location && !$location->getCity()) {
            $location->increaseCountOfCheck();
            $data = $this->getCityInfo($ip);
            if ($data) {
                $location->setCity($this->getCity($data));
            }

            $this->em->flush();
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
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $params = http_build_query([
                'key' => $this->key,
                'ip' => $ip,
                'format' => 'json',
            ]);

            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                ],
            ]);
            try {
                $json = file_get_contents('https://api.ipinfodb.com/v3/ip-city/?' . $params, false, $context);

                return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            } catch (\Throwable $e) {

                return null;
            }
        }

        return null;
    }
}
