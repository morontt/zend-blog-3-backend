<?php

namespace Mtt\BlogBundle\ArgumentResolver;

use Mtt\BlogBundle\DTO\ExternalUserDTO;
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

        dump($request->request);

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        // TODO: Implement resolve() method.
    }
}
