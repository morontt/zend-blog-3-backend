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

#[Route(path: '/api/pygmentsLanguages')]
class PygmentsLanguageController extends BaseController
{
    /**
     * @param Request $request
     * @param PygmentsLanguageRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, PygmentsLanguageRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(true),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getPygmentsLanguageArray($pagination);

        return new JsonResponse($result);
    }

    /**
     * @param PygmentsLanguage $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(PygmentsLanguage $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getPygmentsLanguage($entity);

        return new JsonResponse($result);
    }

    /**
     * @param ValidatorInterface $validator
     * @param Request $request
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['POST'])]
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
     * @param ValidatorInterface $validator
     * @param Request $request
     * @param PygmentsLanguage $entity
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
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
     * @param PygmentsLanguage $entity
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteAction(PygmentsLanguage $entity): JsonResponse
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }

    /**
     * @param PygmentsLanguageRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '/list', name: 'language_choices', options: ['expose' => true], methods: ['GET'])]
    public function ajaxLanguagesListAction(PygmentsLanguageRepository $repository): JsonResponse
    {
        return new JsonResponse($repository->getNamesArray());
    }
}
