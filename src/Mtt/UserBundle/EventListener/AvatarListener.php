<?php

namespace Mtt\UserBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mtt\UserBundle\Event\UserExtraEvent;
use Mtt\UserBundle\OAuth\DataProviderFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class AvatarListener
{
    private DataProviderFactory $providerFactory;

    private LoggerInterface $logger;

    private EntityManagerInterface $em;

    public function __construct(DataProviderFactory $providerFactory, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->providerFactory = $providerFactory;
        $this->logger = $logger;
        $this->em = $em;
    }

    public function onCreate(UserExtraEvent $event): void
    {
        $extraInfo = $event->getExtraInfo();

        try {
            $provider = $this->providerFactory->dataProvider($extraInfo->getDataProvider());
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [
                'extraInfoID' => $extraInfo->getId(),
                'provider' => $extraInfo->getDataProvider(),
            ]);

            return;
        }

        $url = $provider->AvatarURL($extraInfo);
        if ($url) {
            $httpClient = new Client(['timeout' => 3.0]);
            try {
                $response = $httpClient->request('GET', $url);
            } catch (GuzzleException $e) {
                $this->logger->error($e->getMessage(), [
                    'extraInfoID' => $extraInfo->getId(),
                    'provider' => $extraInfo->getDataProvider(),
                ]);

                return;
            }

            $statusCode = $response->getStatusCode();
            $contentType = $response->getHeader('Content-Type')[0] ?? null;

            $this->logger->debug('Avatar download',
                [
                    'extraInfoID' => $extraInfo->getId(),
                    'provider' => $extraInfo->getDataProvider(),
                    'status' => $statusCode,
                    'content-type' => $contentType,
                ]
            );

            if ($statusCode === 200 && $contentType) {
                switch ($contentType) {
                    case 'image/png':
                        $ext = '.png';
                        break;
                    case 'image/jpeg':
                        $ext = '.jpg';
                        break;
                    default:
                        $ext = '.out';
                }

                $user = $extraInfo->getUser();
                $filename = '/var/www/resources/data/pictures/user.' . $user->getId() . $ext;

                $filesystem = new Filesystem();
                try {
                    $filesystem->dumpFile($filename, $response->getBody()->getContents());

                    $user->setAvatarVariant(1 + $user->getAvatarVariant());
                    $this->em->flush();
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage(), [
                        'extraInfoID' => $extraInfo->getId(),
                        'provider' => $extraInfo->getDataProvider(),
                    ]);
                }
            }
        }
    }
}
