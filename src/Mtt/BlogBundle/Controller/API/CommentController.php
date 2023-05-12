<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 28.03.15
 * Time: 21:05
 */

namespace Mtt\BlogBundle\Controller\API;

use Doctrine\ORM\ORMException;
use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\Cron\Daily\CommentGeoLocation;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\Repository\CommentRepository;
use Mtt\BlogBundle\Entity\Repository\ViewCommentRepository;
use Mtt\BlogBundle\Event\ReplyCommentEvent;
use Mtt\BlogBundle\Form\CommentFormType;
use Mtt\BlogBundle\MttBlogEvents;
use Mtt\BlogBundle\Service\CommentManager;
use Mtt\BlogBundle\Service\Tracking;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Xelbot\Telegram\Robot;

/**
 * @Route("/api/comments")
 *
 * Class CommentController
 */
class CommentController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param ViewCommentRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, ViewCommentRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1),
            30
        );

        $result = $this->getDataConverter()
            ->getCommentArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param Comment $entity
     *
     * @return JsonResponse
     */
    public function findAction(Comment $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getComment($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @param Request $request
     * @param Tracking $tracking
     * @param EventDispatcherInterface $dispatcher
     *
     * @return JsonResponse
     */
    public function createAction(
        Request $request,
        Tracking $tracking,
        EventDispatcherInterface $dispatcher
    ): JsonResponse {
        $agent = $tracking->getTrackingAgent($request->server->get('HTTP_USER_AGENT'));

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

        $dispatcher->dispatch(MttBlogEvents::REPLY_COMMENT, new ReplyCommentEvent($comment));

        return new JsonResponse($result, 201);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param Comment $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, Comment $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->saveComment($entity, $request->request->get('comment'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
     *
     * @param Comment $entity
     * @param CommentRepository $repository
     *
     * @throws ORMException
     *
     * @return JsonResponse
     */
    public function deleteAction(Comment $entity, CommentRepository $repository): JsonResponse
    {
        $repository->markAsDeleted($entity);

        return new JsonResponse(true);
    }

    /**
     * @Route("/geo-location", methods={"POST"})
     *
     * @param CommentGeoLocation $geoLocation
     * @param Robot $bot
     *
     * @return JsonResponse
     */
    public function getLocation(CommentGeoLocation $geoLocation, Robot $bot)
    {
        $geoLocation->run();
        $bot->sendMessage('CommentGeoLocation: ' . $geoLocation->getMessage());

        return new JsonResponse(true);
    }

    /**
     * @Route("/external", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createExternalAction(CommentManager $commentManager, Request $request): JsonResponse
    {
        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            dump($data);

            $commentManager->saveComment($data);
        }

        return new JsonResponse(true, 201);
    }
}
