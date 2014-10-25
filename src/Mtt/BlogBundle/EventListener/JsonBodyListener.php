<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 25.10.14
 * Time: 16:08
 */

namespace Mtt\BlogBundle\EventListener;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonBodyListener
{
    /**
     * @param GetResponseEvent $event
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();

        if (!count($request->request->all())
            && in_array($method, array('POST', 'PUT', 'DELETE'))
        ) {
            $contentType = $request->headers->get('Content-Type');

            $format = null === $contentType
                ? $request->getRequestFormat()
                : $request->getFormat($contentType);

            if ($format == 'json') {
                $content = $request->getContent();
                if (!empty($content)) {
                    $data = json_decode($content, true);
                    if (is_array($data)) {
                        $request->request = new ParameterBag($data);
                    } else {
                        throw new BadRequestHttpException('Invalid ' . $format . ' message received');
                    }
                }
            }
        }
    }
}
