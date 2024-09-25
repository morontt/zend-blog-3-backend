<?php

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Entity\Tracking;
use App\Repository\TrackingRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/trackings")
 */
class TrackingController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param TrackingRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, TrackingRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getTrackingArray($pagination, 'trackingAgents');

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param Tracking $entity
     *
     * @return JsonResponse
     */
    public function findAction(Tracking $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getTracking($entity);

        return new JsonResponse($result);
    }
}
