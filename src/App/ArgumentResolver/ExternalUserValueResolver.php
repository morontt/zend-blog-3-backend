<?php

namespace App\ArgumentResolver;

use App\DTO\ExternalUserDTO;
use App\Utils\VerifyEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ExternalUserValueResolver implements ValueResolverInterface
{
    /**
     * @return ExternalUserDTO[]
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (
            ExternalUserDTO::class !== $argument->getType()
            || !$request->request->has('userData')
        ) {
            return [];
        }

        $data = $request->request->all('userData');
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

        return [$dto];
    }
}
