<?php

namespace Mtt\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GitRepoController extends AbstractController
{
    private string $secretToken;

    public function __construct(string $secretToken)
    {
        $this->secretToken = $secretToken;
    }

    /**
     * @Route("/webhook/gitflic", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function webHookAction(Request $request): Response
    {
        $logfile = fopen(APP_VAR_DIR . '/logs/gitflic.log', 'a');

        $token = $request->headers->get('gitflic-webhook-secret');
        if (!hash_equals(sha1($this->secretToken), sha1($token))) {
            fwrite($logfile, date('Y-m-d H:i:s') . ": 403 Forbidden\n");
            fclose($logfile);

            return new Response("Get out!\n", 403);
        }

        $action = $request->request->get('action');
        $push = $request->request->get('push');
        if ($action === 'BRANCH_UPDATE' && !empty($push['ref'])) {
            if ($push['ref'] === 'refs/heads/master') {
                fwrite($logfile, date('Y-m-d H:i:s') . ": Updating...\n");
            } else {
                fwrite($logfile, sprintf("%s: Skip ref: %s\n", date('Y-m-d H:i:s'), $push['ref']));
            }
        }

        if (empty($push['ref']) || empty($action)) {
            fwrite($logfile, date('Y-m-d H:i:s') . ": Something wrong...\n");
            $content = $request->getContent();
            if (!empty($content)) {
                fwrite($logfile, date('Y-m-d H:i:s') . ': ' . $content . "\n");
            }
        }

        fclose($logfile);

        return new Response("ok\n");
    }
}
