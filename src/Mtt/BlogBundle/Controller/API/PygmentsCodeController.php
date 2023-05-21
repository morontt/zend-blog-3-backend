<?php

namespace Mtt\BlogBundle\Controller\API;

use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\Entity\PygmentsCode;
use Mtt\BlogBundle\Entity\Repository\PygmentsCodeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pygmentsCodes")
 */
class PygmentsCodeController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param PygmentsCodeRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, PygmentsCodeRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getPygmentsCodeArray($pagination, 'language');

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param PygmentsCode $entity
     *
     * @return JsonResponse
     */
    public function findAction(PygmentsCode $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getPygmentsCode($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $result = $this->getDataConverter()
            ->savePygmentsCode(new PygmentsCode(), $request->request->get('pygmentsCode'));

        return new JsonResponse($result, 201);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param PygmentsCode $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, PygmentsCode $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->savePygmentsCode($entity, $request->request->get('pygmentsCode'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
     *
     * @param PygmentsCode $entity
     *
     * @throws \Doctrine\ORM\ORMException
     *
     * @return JsonResponse
     */
    public function deleteAction(PygmentsCode $entity): JsonResponse
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }
}
