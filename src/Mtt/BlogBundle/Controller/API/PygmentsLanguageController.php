<?php

namespace Mtt\BlogBundle\Controller\API;

use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\Entity\PygmentsLanguage;
use Mtt\BlogBundle\Entity\Repository\PygmentsLanguageRepository;
use Mtt\BlogBundle\Form\PygmentsLanguageFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pygmentsLanguages")
 */
class PygmentsLanguageController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param PygmentsLanguageRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, PygmentsLanguageRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(true),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getPygmentsLanguageArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param PygmentsLanguage $entity
     *
     * @return JsonResponse
     */
    public function findAction(PygmentsLanguage $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getPygmentsLanguage($entity);

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
        $form = $this->createObjectForm('pygmentsLanguage', PygmentsLanguageFormType::class);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()
            ->savePygmentsLanguage(new PygmentsLanguage(), $formData['pygmentsLanguage']);

        return new JsonResponse($result, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param PygmentsLanguage $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, PygmentsLanguage $entity): JsonResponse
    {
        $form = $this->createObjectForm('pygmentsLanguage', PygmentsLanguageFormType::class, true);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()
            ->savePygmentsLanguage($entity, $formData['pygmentsLanguage']);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
     *
     * @param PygmentsLanguage $entity
     *
     * @throws \Doctrine\ORM\ORMException
     *
     * @return JsonResponse
     */
    public function deleteAction(PygmentsLanguage $entity): JsonResponse
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }

    /**
     * @Route("/list", name="language_choices", options={"expose"=true}, methods={"GET"})
     *
     * @param PygmentsLanguageRepository $repository
     *
     * @return JsonResponse
     */
    public function ajaxLanguagesListAction(PygmentsLanguageRepository $repository): JsonResponse
    {
        return new JsonResponse($repository->getNamesArray());
    }
}
