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
use App\Entity\User;
use App\Event\CommentEvent;
use App\Event\DeleteCommentEvent;
use App\Exception\NotAllowedCommentException;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\ViewCommentRepository;
use App\Service\CommentManager;
use App\Service\Tracking;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Xelbot\Telegram\Robot;

#[Route(path: '/api/comments')]
class CommentController extends BaseController
{
    protected array $errorsPathMap = [
        'children[text].data' => 'comment_text',
        'children[commentator].children[name].data' => 'name',
        'children[commentator].children[email].data' => 'email',
        'children[commentator].children[website].data' => 'website',
    ];

    /**
     * @param Request $request
     * @param ViewCommentRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
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
     * @param Comment $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(Comment $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getComment($entity);

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     * @param Tracking $tracking
     * @param EventDispatcherInterface $dispatcher
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['POST'])]
    public function createAction(
        Request $request,
        Tracking $tracking,
        EventDispatcherInterface $dispatcher,
    ): JsonResponse {
        $agent = $tracking->getTrackingAgent($request->server->get('HTTP_USER_AGENT'));

        $user = $this->getUser();
        if (!$user || !($user instanceof User)) {
            throw new RuntimeException('User is null or not supported');
        }

        $comment = new Comment();
        $comment
            ->setUser($user)
            ->setTrackingAgent($agent)
            ->setIpAddress($request->getClientIp())
        ;

        $commentData = $this->getArrayData($request, 'comment');
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
     * @param Request $request
     * @param Comment $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateAction(Request $request, Comment $entity): JsonResponse
    {
        $commentData = $this->getArrayData($request, 'comment');

        $result = $this
            ->getDataConverter()
            ->saveComment($entity, $commentData)
        ;

        return new JsonResponse($result);
    }

    /**
     * @param Comment $entity
     * @param CommentRepository $repository
     * @param EventDispatcherInterface $dispatcher
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
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
     * @param CommentGeoLocation $geoLocation
     * @param Robot $bot
     *
     * @return JsonResponse
     */
    #[Route(path: '/geo-location', methods: ['POST'])]
    public function getLocation(CommentGeoLocation $geoLocation, Robot $bot): JsonResponse
    {
        $geoLocation->run();
        $bot->sendMessage('CommentGeoLocation: ' . $geoLocation->getMessage());

        return new JsonResponse(true);
    }

    /**
     * @param CommentManager $commentManager
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route(path: '/external', methods: ['POST'])]
    public function createExternalAction(
        CommentManager $commentManager,
        PostRepository $postRepo,
        Request $request,
    ): JsonResponse {
        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $article = $postRepo->find($formData->topicId >> 7);
        if (!$article || $article->hashedId() !== $formData->topicId) {
            throw $this->createAccessDeniedException();
        }

        $formData->topicId >>= 7;

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
