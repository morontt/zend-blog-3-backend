<?php

namespace App\ArgumentResolver;

use Doctrine\Persistence\ManagerRegistry;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class EntityValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private ManagerRegistry $registry,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (!$request->attributes->has('id')) {
            return false;
        }

        return (bool)$this->registry->getManagerForClass($argument->getType());
    }

    /**
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $objectManager = $this->registry->getManagerForClass($argument->getType());
        // @phpstan-ignore argument.templateType
        $repository = $objectManager->getRepository($argument->getType());

        yield $repository->findOneBy(['id' => $request->attributes->get('id')]);
    }
}
