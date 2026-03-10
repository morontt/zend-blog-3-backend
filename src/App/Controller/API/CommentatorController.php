<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 0:06
 */

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Entity\Commentator;
use App\Entity\ViewCommentator;
use App\Event\UpdateCommentatorEvent;
use App\Repository\CommentatorRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/commentators')]
class CommentatorController extends BaseController
{
    /**
     * @param Request $request
     * @param CommentatorRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, CommentatorRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getCommentatorArray($pagination);

        return new JsonResponse($result);
    }

    /**
     * @param ViewCommentator $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(ViewCommentator $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getCommentator($entity);

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     * @param EventDispatcherInterface $dispatcher
     * @param Commentator $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateAction(
        Request $request,
        EventDispatcherInterface $dispatcher,
        Commentator $entity,
    ): JsonResponse {
        $commentator = $this->getArrayData($request, 'commentator');

        $result = $this
            ->getDataConverter()
            ->saveCommentator($entity, $commentator)
        ;

        $dispatcher->dispatch(new UpdateCommentatorEvent($entity));

        return new JsonResponse($result);
    }
}
