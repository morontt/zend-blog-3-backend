<?php

namespace Mtt\BlogBundle\Controller\API;

use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\Entity\Repository\TelegramUserRepository;
use Mtt\BlogBundle\Entity\TelegramUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/telegramUsers")
 */
class TelegramUserController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param TelegramUserRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, TelegramUserRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getTelegramUserArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param TelegramUser $entity
     *
     * @return JsonResponse
     */
    public function findAction(TelegramUser $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getTelegramUser($entity);

        return new JsonResponse($result);
    }
}
