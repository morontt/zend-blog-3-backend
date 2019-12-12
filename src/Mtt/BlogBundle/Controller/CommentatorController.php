<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 0:06
 */

namespace Mtt\BlogBundle\Controller;

use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\ViewCommentator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request)
    {
        $pagination = $this->paginate(
            $this->getCommentatorRepository()->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getCommentatorArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param ViewCommentator $entity
     *
     * @return JsonResponse
     */
    public function findAction(ViewCommentator $entity)
    {
        $result = $this->getDataConverter()
            ->getCommentator($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("PUT")
     *
     * @param Request $request
     * @param Commentator $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, Commentator $entity)
    {
        $result = $this->getDataConverter()
            ->saveCommentator($entity, $request->request->get('commentator'));

        return new JsonResponse($result);
    }
}
