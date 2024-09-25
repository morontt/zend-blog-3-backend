<?php

namespace App\Controller\API;

use App\API\Transformers\PygmentsLanguageTransformer;
use App\Controller\BaseController;
use App\Entity\PygmentsLanguage;
use App\Form\PygmentsLanguageFormType;
use App\Repository\PygmentsLanguageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @param ValidatorInterface $validator
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(ValidatorInterface $validator, Request $request): JsonResponse
    {
        $form = $this->createObjectForm('pygmentsLanguage', PygmentsLanguageFormType::class);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entity = new PygmentsLanguage();
        PygmentsLanguageTransformer::reverseTransform($entity, $formData['pygmentsLanguage']);

        $errors = $this->validate($validator, $entity);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->em->persist($entity);
        $this->em->flush();

        return new JsonResponse($this->getDataConverter()->getPygmentsLanguage($entity), Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param ValidatorInterface $validator
     * @param Request $request
     * @param PygmentsLanguage $entity
     *
     * @return JsonResponse
     */
    public function updateAction(ValidatorInterface $validator, Request $request, PygmentsLanguage $entity): JsonResponse
    {
        $form = $this->createObjectForm('pygmentsLanguage', PygmentsLanguageFormType::class, true);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        PygmentsLanguageTransformer::reverseTransform($entity, $formData['pygmentsLanguage']);

        $errors = $this->validate($validator, $entity);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->em->persist($entity);
        $this->em->flush();

        return new JsonResponse($this->getDataConverter()->getPygmentsLanguage($entity));
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
