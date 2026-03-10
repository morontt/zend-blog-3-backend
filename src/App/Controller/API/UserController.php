<?php

namespace App\Controller\API;

use App\API\Transformers\UserTransformer;
use App\Controller\BaseController;
use App\DTO\ExternalUserDTO;
use App\Entity\User;
use App\Event\UserEvent;
use App\Event\UserExtraEvent;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/api/users')]
class UserController extends BaseController
{
    protected array $errorsPathMap = [
        'children[user].children[username].data' => 'username',
        'children[user].children[displayName].data' => 'displayName',
        'children[user].children[email].data' => 'email',
        'username' => 'username',
        'email' => 'email',
    ];

    /**
     * @param Request $request
     * @param UserRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, UserRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getUserArray($pagination);

        return new JsonResponse($result);
    }

    /**
     * @param User $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(User $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getUser($entity);

        return new JsonResponse($result);
    }

    /**
     * @param ValidatorInterface $validator
     * @param EventDispatcherInterface $dispatcher
     * @param Request $request
     * @param User $entity
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateAction(
        ValidatorInterface $validator,
        EventDispatcherInterface $dispatcher,
        Request $request,
        User $entity,
    ): JsonResponse {
        $form = $this->createObjectForm('user', UserFormType::class, true);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        UserTransformer::reverseTransform($entity, $formData['user']);

        $errors = $this->validate($validator, $entity);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->getEm()->persist($entity);
        $this->getEm()->flush();

        $dispatcher->dispatch(new UserEvent($entity));

        return new JsonResponse($this->getDataConverter()->getUser($entity));
    }

    /**
     * @param ExternalUserDTO $userDTO
     * @param UserManager $userManager
     * @param ValidatorInterface $validator
     * @param EventDispatcherInterface $dispatcher
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route(path: '/external', methods: ['POST'])]
    public function createExternalAction(
        ExternalUserDTO $userDTO,
        UserManager $userManager,
        ValidatorInterface $validator,
        EventDispatcherInterface $dispatcher,
        Request $request,
    ): JsonResponse {
        $findResult = $userManager->findByExternalDTO($userDTO);
        $user = $findResult->getUser();
        if (!$user) {
            $user = $userManager->createFromExternalDTO($userDTO);

            $errors = $this->validate($validator, $user);
            if (count($errors) > 0) {
                return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $this->getEm()->persist($user);
            $this->getEm()->flush();
        }

        if (!$findResult->found()) {
            $userInfo = $userManager->saveUserExtraInfo(
                $userDTO,
                $user,
                $request->request->get('ipAddress'),
                $request->request->get('userAgent')
            );

            $dispatcher->dispatch(new UserExtraEvent($userInfo));
        }

        return new JsonResponse(
            $this->getDataConverter()->getUser($user),
            $findResult->found() ? Response::HTTP_OK : Response::HTTP_CREATED
        );
    }
}
