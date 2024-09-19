<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.11.16
 * Time: 13:05
 */

namespace App\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\GeoLocation;
use App\Entity\GeoLocationCity;
use App\Entity\GeoLocationCountry;
use App\Service\IpInfo\IpInfoClientInterface;
use App\Service\IpInfo\LocationInfo;

class IpInfo
{
    /**
     * @var EntityManager
     */
    private $em;

    private IpInfoClientInterface $ipInfoClient;

    /**
     * @param EntityManagerInterface $em
     * @param IpInfoClientInterface $ipInfoClient
     */
    public function __construct(EntityManagerInterface $em, IpInfoClientInterface $ipInfoClient)
    {
        $this->em = $em;
        $this->ipInfoClient = $ipInfoClient;
    }

    /**
     * @param string $ip
     *
     * @return GeoLocation|null
     */
    public function getLocationByIp(string $ip)
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
     * Following RFC 1918, Section 3
     *
     * 10.0.0.0    - 10.255.255.255  (10/8 prefix)
     * 172.16.0.0  - 172.31.255.255  (172.16/12 prefix)
     * 192.168.0.0 - 192.168.255.255 (192.168/16 prefix)
     *
     * @param string $ip
     *
     * @return bool
     */
    public function isPrivateIP(string $ip): bool
    {
        $ip4 = array_map(function ($b) { return (int)$b; }, explode('.', $ip));

        return $ip4[0] == 10
            || ($ip4[0] == 172 && ($ip4[1] & 0xf0) == 16)
            || ($ip4[0] == 192 && $ip4[1] == 168);
    }

    /**
     * @param LocationInfo $data
     *
     * @return GeoLocationCity|null
     */
    protected function getCity(LocationInfo $data)
    {
        $city = null;
        if (!empty($data->cityName) && !empty($data->regionName)) {
            $country = $this->getCountry($data);
            if ($country) {
                $city = $this->em->getRepository('MttBlogBundle:GeoLocationCity')->findOneBy([
                    'city' => $data->cityName,
                    'region' => $data->regionName,
                    'country' => $country->getId(),
                ]);

                if (!$city) {
                    $city = new GeoLocationCity();
                    $city
                        ->setCity($data->cityName)
                        ->setRegion($data->regionName)
                        ->setLatitude($data->latitude)
                        ->setLongitude($data->longitude)
                        ->setTimeZone($data->timeZone)
                        ->setZip($data->zipCode)
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
     * @param LocationInfo $data
     *
     * @return GeoLocationCountry|null
     */
    protected function getCountry(LocationInfo $data)
    {
        $country = null;
        if (!empty($data->countryCode) && !empty($data->countryName)) {
            $country = $this->em
                ->getRepository('MttBlogBundle:GeoLocationCountry')
                ->findOneByCode($data->countryCode);

            if (!$country) {
                $country = new GeoLocationCountry();
                $country
                    ->setCode($data->countryCode)
                    ->setName($data->countryName)
                ;

                $this->em->persist($country);
                $this->em->flush();
            }
        }

        return $country;
    }

    /**
     * @param $ip
     *
     * @return LocationInfo|null
     */
    protected function getCityInfo($ip): ?LocationInfo
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            if ($this->isPrivateIP($ip)) {
                return LocationInfo::createFromArray([
                    'countryCode' => '-',
                    'countryName' => '-',
                    'regionName' => '-',
                    'cityName' => '-',
                ]);
            }

            return $this->ipInfoClient->getLocationInfo($ip);
        }

        return null;
    }
}
