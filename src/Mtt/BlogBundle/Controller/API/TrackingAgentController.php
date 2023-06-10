<?php

namespace Mtt\BlogBundle\Controller\API;

use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\Entity\Repository\TrackingAgentRepository;
use Mtt\BlogBundle\Entity\TrackingAgent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/userAgents")
 */
class TrackingAgentController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param TrackingAgentRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, TrackingAgentRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1),
            50
        );

        $result = $this->getDataConverter()
            ->getUserAgentArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param TrackingAgent $entity
     *
     * @return JsonResponse
     */
    public function findAction(TrackingAgent $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getUserAgent($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param TrackingAgent $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, TrackingAgent $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->saveTrackingAgent($entity, $request->request->get('userAgent'));

        return new JsonResponse($result);
    }
}
