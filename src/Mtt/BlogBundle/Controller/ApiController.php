<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 16.11.14
 * Time: 17:43
 */

namespace Mtt\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api")
 *
 * Class ApiController
 */
class ApiController extends BaseController
{
    /**
     * @Route("/")
     *
     * @return Response
     */
    public function infoAction()
    {
        $bot = $this->get('mtt_blog.telegram_bot');
        $bot->sendMessage('Превед ' . substr(sha1(date('D, d M Y H:i:s')), 0, 7) . ' :)');

        return new Response('Hi :)');
    }
}
