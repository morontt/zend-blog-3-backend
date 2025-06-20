<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 28.03.15
 * Time: 21:05
 */

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Cron\Daily\CommentGeoLocation;
use App\Entity\Comment;
use App\Event\CommentEvent;
use App\Event\DeleteCommentEvent;
use App\Exception\NotAllowedCommentException;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ViewCommentRepository;
use App\Service\CommentManager;
use App\Service\Tracking;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Xelbot\Telegram\Robot;

/**
 * @Route("/api/comments")
 *
 * Class CommentController
 */
class CommentController extends BaseController
{
    protected array $errorsPathMap = [
        'children[text].data' => 'comment_text',
        'children[commentator].children[name].data' => 'name',
        'children[commentator].children[email].data' => 'email',
        'children[commentator].children[website].data' => 'website',
    ];

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
     * @throws \Doctrine\ORM\Exception\NotSupported
     *
     * @return JsonResponse
     */
    public function createAction(
        Request $request,
        Tracking $tracking,
        EventDispatcherInterface $dispatcher,
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
            $parent = $this->getEm()->getRepository(Comment::class)->find((int)$commentData['parent']);
            if ($parent) {
                $comment
                    ->setParent($parent)
                    ->setPost($parent->getPost())
                ;
            }
        }

        $this->getDataConverter()
            ->saveComment($comment, $commentData);

        $dispatcher->dispatch(new CommentEvent($comment));

        return new JsonResponse($this->getDataConverter()->getComment($comment), Response::HTTP_CREATED);
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
     * @param EventDispatcherInterface $dispatcher
     *
     * @return JsonResponse
     */
    public function deleteAction(
        Comment $entity,
        CommentRepository $repository,
        EventDispatcherInterface $dispatcher,
    ): JsonResponse {
        $repository->markAsDeleted($entity);
        $dispatcher->dispatch(new DeleteCommentEvent($entity));

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
    public function getLocation(CommentGeoLocation $geoLocation, Robot $bot): JsonResponse
    {
        $geoLocation->run();
        $bot->sendMessage('CommentGeoLocation: ' . $geoLocation->getMessage());

        return new JsonResponse(true);
    }

    /**
     * @Route("/external", methods={"POST"})
     *
     * @param CommentManager $commentManager
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createExternalAction(CommentManager $commentManager, Request $request): JsonResponse
    {
        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $comment = $commentManager->saveExternalComment($formData);
        } catch (NotAllowedCommentException $e) {
            throw $this->createAccessDeniedException();
        }

        return new JsonResponse(
            $this->getDataConverter()->getComment($comment),
            Response::HTTP_CREATED
        );
    }
}
