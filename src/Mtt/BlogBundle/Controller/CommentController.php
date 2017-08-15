<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 28.03.15
 * Time: 21:05
 */

namespace Mtt\BlogBundle\Controller;

use Mtt\BlogBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/comments")
 *
 * Class CommentController
 */
class CommentController extends BaseController
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
            $this->getViewCommentRepository()->getListQuery(),
            $request->query->get('page', 1),
            30
        );

        $result = $this->getDataConverter()
            ->getCommentArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param Comment $entity
     *
     * @return JsonResponse
     */
    public function findAction(Comment $entity)
    {
        $result = $this->getDataConverter()
            ->getComment($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $agent = $this->get('mtt_blog.tracking')->getTrackingAgent($request->server->get('HTTP_USER_AGENT'));

        $comment = new Comment();
        $comment
            ->setUser($this->getUser())
            ->setTrackingAgent($agent)
            ->setIpAddress($request->getClientIp())
        ;

        $commentData = $request->request->get('comment');
        if ($commentData['parent']) {
            $parent = $this->getEm()->getRepository('MttBlogBundle:Comment')->find((int)$commentData['parent']);
            if ($parent) {
                $comment
                    ->setParent($parent)
                    ->setPost($parent->getPost())
                ;
            }
        }

        $result = $this->getDataConverter()
            ->saveComment($comment, $commentData);

        return new JsonResponse($result, 201);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("PUT")
     *
     * @param Request $request
     * @param Comment $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, Comment $entity)
    {
        $result = $this->getDataConverter()
            ->saveComment($entity, $request->request->get('comment'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("DELETE")
     *
     * @param Comment $entity
     *
     * @return JsonResponse
     */
    public function deleteAction(Comment $entity)
    {
        $entity->setDeleted(true);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }
}
