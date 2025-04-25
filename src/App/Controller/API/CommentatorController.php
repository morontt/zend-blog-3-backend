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
use App\Repository\CommentatorRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/commentators")
 *
 * Class CommentatorController
 */
class CommentatorController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param CommentatorRepository $repository
     *
     * @return JsonResponse
     */
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
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param ViewCommentator $entity
     *
     * @return JsonResponse
     */
    public function findAction(ViewCommentator $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getCommentator($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param Commentator $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, Commentator $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->saveCommentator($entity, $request->request->get('commentator'));

        return new JsonResponse($result);
    }
}
