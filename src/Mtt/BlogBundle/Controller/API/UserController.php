<?php

namespace Mtt\BlogBundle\Controller\API;

use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\DTO\ExternalUserDTO;
use Mtt\UserBundle\Service\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/users")
 */
class UserController extends BaseController
{
    /**
     * @Route("/external", methods={"POST"})
     *
     * @param ExternalUserDTO $userDTO
     * @param UserManager $userManager
     * @param ValidatorInterface $validator
     *
     * @throws \Exception
     *
     * @return JsonResponse
     */
    public function createExternalAction(
        ExternalUserDTO $userDTO,
        UserManager $userManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $user = $userManager->findByExternalDTO($userDTO);
        if (!$user) {
            $user = $userManager->createFromExternalDTO($userDTO);

            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                throw new \RuntimeException();
            }

            $this->em->persist($user);
            $this->em->flush();
        }

        return new JsonResponse(
            $this->getDataConverter()->getUser($user),
            Response::HTTP_CREATED
        );
    }
}
