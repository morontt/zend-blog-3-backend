<?php

namespace App\Controller\API;

use App\API\Transformers\UserTransformer;
use App\Controller\BaseController;
use App\DTO\ExternalUserDTO;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Event\UserEvent;
use App\Event\UserExtraEvent;
use App\Events;
use App\Service\UserManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/users")
 */
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
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param UserRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, UserRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getUserArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param User $entity
     *
     * @return JsonResponse
     */
    public function findAction(User $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getUser($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param ValidatorInterface $validator
     * @param EventDispatcherInterface $dispatcher
     * @param Request $request
     * @param User $entity
     *
     * @return JsonResponse
     */
    public function updateAction(
        ValidatorInterface $validator,
        EventDispatcherInterface $dispatcher,
        Request $request,
        User $entity
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

        $this->em->persist($entity);
        $this->em->flush();

        $dispatcher->dispatch(Events::USER_UPDATED, new UserEvent($entity));

        return new JsonResponse($this->getDataConverter()->getUser($entity));
    }

    /**
     * @Route("/external", methods={"POST"})
     *
     * @param ExternalUserDTO $userDTO
     * @param UserManager $userManager
     * @param ValidatorInterface $validator
     * @param EventDispatcherInterface $dispatcher
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createExternalAction(
        ExternalUserDTO $userDTO,
        UserManager $userManager,
        ValidatorInterface $validator,
        EventDispatcherInterface $dispatcher,
        Request $request
    ): JsonResponse {
        [$user, $foundInfo] = $userManager->findByExternalDTO($userDTO);
        if (!$user) {
            $user = $userManager->createFromExternalDTO($userDTO);

            $errors = $this->validate($validator, $user);
            if (count($errors) > 0) {
                return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $this->em->persist($user);
            $this->em->flush();
        }

        if (!$foundInfo) {
            $userInfo = $userManager->saveUserExtraInfo(
                $userDTO,
                $user,
                $request->request->get('ipAddress'),
                $request->request->get('userAgent')
            );

            $dispatcher->dispatch(Events::EXTERNAL_USER_CREATED, new UserExtraEvent($userInfo));
        }

        return new JsonResponse(
            $this->getDataConverter()->getUser($user),
            $foundInfo ? Response::HTTP_OK : Response::HTTP_CREATED
        );
    }
}