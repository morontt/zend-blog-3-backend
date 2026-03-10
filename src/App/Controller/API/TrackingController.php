<?php

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Entity\Tracking;
use App\Repository\TrackingRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/trackings')]
class TrackingController extends BaseController
{
    /**
     * @param Request $request
     * @param TrackingRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, TrackingRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getTrackingArray($pagination, 'trackingAgents');

        return new JsonResponse($result);
    }

    /**
     * @param Tracking $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(Tracking $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getTracking($entity);

        return new JsonResponse($result);
    }
}
