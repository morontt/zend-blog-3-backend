<?php

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Entity\PygmentsCode;
use App\Event\PygmentCodeEvent;
use App\Form\PygmentsCodeFormType;
use App\Repository\PostRepository;
use App\Repository\PygmentsCodeRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/pygmentsCodes')]
class PygmentsCodeController extends BaseController
{
    /**
     * @param Request $request
     * @param PygmentsCodeRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, PygmentsCodeRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getPygmentsCodeArray($pagination, 'language');

        return new JsonResponse($result);
    }

    /**
     * @param PygmentsCode $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(PygmentsCode $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getPygmentsCode($entity);

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['POST'])]
    public function createAction(Request $request): JsonResponse
    {
        $form = $this->createObjectForm('pygmentsCode', PygmentsCodeFormType::class);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()->savePygmentsCode(new PygmentsCode(), $formData['pygmentsCode']);

        return new JsonResponse($result, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param PygmentsCode $entity
     * @param EventDispatcherInterface $dispatcher
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateAction(
        Request $request,
        PygmentsCode $entity,
        EventDispatcherInterface $dispatcher,
    ): JsonResponse {
        $form = $this->createObjectForm('pygmentsCode', PygmentsCodeFormType::class, true);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()->savePygmentsCode($entity, $formData['pygmentsCode']);

        $dispatcher->dispatch(new PygmentCodeEvent($entity));

        return new JsonResponse($result);
    }

    /**
     * @param PygmentsCode $entity
     * @param PostRepository $repository
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteAction(PygmentsCode $entity, PostRepository $repository): JsonResponse
    {
        $posts = $repository->getPostsByCodeSnippet($entity->getId());
        if (count($posts)) {
            return new JsonResponse(
                ['error' => 'this code snippet is used snippet'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }
}
