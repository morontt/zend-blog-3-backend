<?php

namespace App\Service;

use App\DTO\CommentDTO;
use App\Entity\Comment;
use App\Event\CommentEvent;
use App\Events;
use App\Exception\NotAllowedCommentException;
use App\Repository\CommentatorRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Laminas\Filter\StripTags;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CommentManager
{
    /**
     * @var Tracking
     */
    private $tracking;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var CommentatorRepository
     */
    private $commentatorRepo;

    /**
     * @var CommentRepository
     */
    private $commentRepo;

    /**
     * @var PostRepository
     */
    private $postRepo;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        Tracking $tracking,
        EventDispatcherInterface $dispatcher,
        CommentatorRepository $commentatorRepo,
        CommentRepository $commentRepo,
        UserRepository $userRepository,
        PostRepository $postRepo
    ) {
        $this->tracking = $tracking;
        $this->dispatcher = $dispatcher;
        $this->commentatorRepo = $commentatorRepo;
        $this->userRepository = $userRepository;
        $this->commentRepo = $commentRepo;
        $this->postRepo = $postRepo;
    }

    /**
     * @param CommentDTO $commentData
     *
     * @throws NotAllowedCommentException
     *
     * @return Comment
     */
    public function saveExternalComment(CommentDTO $commentData): Comment
    {
        $agent = $commentData->userAgent ? $this->tracking->getTrackingAgent($commentData->userAgent) : null;

        $post = $this->postRepo->find($commentData->topicId);
        if (!($post && !$post->isDisableComments())) {
            throw new NotAllowedCommentException();
        }

        $filter = new StripTags([
            'allowTags' => ['a', 's', 'b', 'i', 'em', 'strong', 'img', 'p'],
            'allowAttribs' => ['src', 'href'],
        ]);

        $comment = new Comment();
        $comment
            ->setTrackingAgent($agent)
            ->setIpAddress($commentData->ipAddress)
            ->setText($filter->filter($commentData->text))
            ->setPost($post)
        ;

        if ($commentData->parentId > 0) {
            $parent = $this->commentRepo->find($commentData->parentId);
            if ($parent) {
                $comment->setParent($parent);
            }
        }

        if ($commentData->commentator) {
            $commentator = $this->commentatorRepo->findOrCreate($commentData->commentator);
            $comment->setCommentator($commentator);
        } elseif ($commentData->user && $user = $this->userRepository->find($commentData->user->id)) {
            $comment->setUser($user);
        } else {
            throw new \LogicException('Comment without sender');
        }

        $this->commentRepo->save($comment);

        $this->dispatcher->dispatch(Events::REPLY_COMMENT, new CommentEvent($comment));

        return $comment;
    }
}
