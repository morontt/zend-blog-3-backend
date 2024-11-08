<?php

namespace App\ArgumentResolver;

use App\Entity\Post;
use App\Repository\PostRepository;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PostValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var PostRepository
     */
    private $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if (Post::class !== $argument->getType()) {
            return false;
        }

        return $request->attributes->has('slug');
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->repository->findOneBy(['url' => $request->attributes->get('slug')]);
    }
}
