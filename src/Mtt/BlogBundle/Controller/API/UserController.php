<?php

namespace Mtt\BlogBundle\Controller\API;

use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\DTO\ExternalUserDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/users")
 */
class UserController extends BaseController
{
    /**
     * @Route("/external", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createExternalAction(ExternalUserDTO $userDTO, Request $request): JsonResponse
    {
        dump($userDTO);

        return new JsonResponse(['status' => 'ok'], Response::HTTP_CREATED);
    }
}
