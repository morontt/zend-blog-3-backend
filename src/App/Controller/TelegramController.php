<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 13.09.17
 * Time: 22:07
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Xelbot\Telegram\Robot;

class TelegramController extends AbstractController
{
    /**
     * @var string
     */
    private string $secretToken;

    /**
     * @var Robot
     */
    private Robot $telegramBot;

    /**
     * @param Robot $telegramBot
     * @param string $secretToken
     */
    public function __construct(Robot $telegramBot, string $secretToken)
    {
        $this->telegramBot = $telegramBot;
        $this->secretToken = $secretToken;
    }

    /**
     * @Route("/telegram/{token}", methods={"POST"})
     *
     * @param Request $request
     * @param string $token
     *
     * @return Response
     */
    public function webHookAction(Request $request, string $token): Response
    {
        if (!hash_equals(sha1($this->secretToken), sha1($token))) {
            return new Response("Get out!\n", 403);
        }

        $this->telegramBot->handle($request->request->all());

        return new Response("ok\n");
    }
}
