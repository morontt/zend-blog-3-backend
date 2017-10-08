<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.09.17
 * Time: 22:07
 */

namespace Mtt\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TelegramController extends BaseController
{
    /**
     * @Route("/telegram/{token}")
     * @Method("POST")
     *
     * @param Request $request
     * @param string $token
     *
     * @return Response
     */
    public function webHookAction(Request $request, string $token): Response
    {
        $secretToken = $this->container->getParameter('telegram_webhook_token');
        if (!hash_equals(sha1($secretToken), sha1($token))) {
            return new Response("Get out!\n", 403);
        }

        $bot = $this->get('mtt_blog.telegram_bot');
        $bot->handle($request->request->all());

        return new Response("ok\n");
    }
}
