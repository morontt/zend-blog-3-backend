<?php

namespace Mtt\BlogBundle\ArgumentResolver;

use Mtt\BlogBundle\DTO\ExternalUserDTO;
use Mtt\BlogBundle\Utils\VerifyEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ExternalUserValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if (ExternalUserDTO::class !== $argument->getType()) {
            return false;
        }

        return $request->request->has('userData');
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $data = $request->request->get('userData');
        $dto = new ExternalUserDTO();

        $dto->id = $data['id'];
        $dto->dataProvider = $data['dataProvider'];
        $dto->rawData = $data['rawData'];

        $email = $data['email'] ?? null;
        if (!empty($email)) {
            $dto->email = VerifyEmail::normalize($email);
        }

        $dto->username = $data['username'] ?? null;
        $dto->displayName = $data['displayName'] ?? null;
        $dto->firstName = $data['firstName'] ?? null;
        $dto->lastName = $data['lastName'] ?? null;
        $dto->gender = $data['gender'] ?? null;
        $dto->avatar = $data['avatar'] ?? null;

        yield $dto;
    }
}
