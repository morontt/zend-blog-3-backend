<?php

namespace Mtt\BlogBundle\Controller\API;

use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\Entity\Repository\TelegramUpdateRepository;
use Mtt\BlogBundle\Entity\TelegramUpdate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/telegramUpdates")
 */
class TelegramUpdateController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param TelegramUpdateRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, TelegramUpdateRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getTelegramUpdateArray($pagination, 'telegramUser');

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param TelegramUpdate $entity
     *
     * @return JsonResponse
     */
    public function findAction(TelegramUpdate $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getTelegramUpdate($entity);

        return new JsonResponse($result);
    }
}
