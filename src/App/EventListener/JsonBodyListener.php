<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 25.10.14
 * Time: 16:08
 */

namespace App\EventListener;

use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonBodyListener
{
    private LoggerInterface $logger;

    /**
     * @param RequestEvent $event
     *
     * @throws BadRequestHttpException
     * @throws JsonException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $method = $request->getMethod();

        if (in_array($method, ['POST', 'PUT', 'DELETE'])
            && !count($request->request->all())
        ) {
            $contentType = $request->headers->get('Content-Type');

            $format = null === $contentType
                ? $request->getRequestFormat()
                : $request->getFormat($contentType);

            if ($format === 'json') {
                $content = $request->getContent();
                if (!empty($content)) {
                    $this->telegramRawLog($request->getRequestUri(), $content);
                    $data = json_decode(
                        $content,
                        true,
                        512,
                        JSON_BIGINT_AS_STRING | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR
                    );
                    if (is_array($data)) {
                        $request->request = new ParameterBag($data);
                    } else {
                        throw new BadRequestHttpException('Invalid ' . $format . ' message received');
                    }
                }
            }
        }
    }

    public function setTelegramLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    private function telegramRawLog($uri, $content): void
    {
        if (strpos($uri, '/telegram/') !== false) {
            $this->logger->debug('Raw telegram request', ['content' => $content]);
        }
    }
}
