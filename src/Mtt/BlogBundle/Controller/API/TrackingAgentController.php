<?php

namespace Mtt\BlogBundle\Controller\API;

use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\Entity\Repository\TrackingAgentRepository;
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
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getUserAgentArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }
}
