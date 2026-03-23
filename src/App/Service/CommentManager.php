<?php

namespace App\Service;

use App\DTO\CommentDTO;
use App\Entity\Comment;
use App\Event\CommentEvent;
use App\Exception\NotAllowedCommentException;
use App\Repository\CommentatorRepository;
use App\Repository\CommentRepository;
use App\Repository\GeoLocationRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use Laminas\Filter\StripTags;
use LogicException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CommentManager
{
    public function __construct(
        private Tracking $tracking,
        private EventDispatcherInterface $dispatcher,
        private CommentatorRepository $commentatorRepo,
        private CommentRepository $commentRepo,
        private UserRepository $userRepository,
        private PostRepository $postRepo,
        private GeoLocationRepository $geoRepository,
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
            'allowAttribs' => ['src', 'href', 'class'],
        ]);

        $comment = new Comment();
        $comment
            ->setTrackingAgent($agent)
            ->setText($filter->filter($commentData->text))
            ->setPost($post)
        ;

        if (filter_var($commentData->ipAddress, FILTER_VALIDATE_IP)) {
            $comment
                ->setIpAddress($commentData->ipAddress)
                ->setGeoLocation($this->geoRepository->findOneByIpAddress($commentData->ipAddress))
            ;
        }

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
            throw new LogicException('Comment without sender');
        }

        $dtForce = null;
        if ($commentData->forceCreatedAt) {
            try {
                $dtForce = new DateTime($commentData->forceCreatedAt);
            } catch (Exception $e) {
            }
        }
        $comment->setForceCreatedAt($dtForce);

        if ($commentData->deleted) {
            $comment->setDeleted(true);
        }

        $this->commentRepo->save($comment);

        $this->dispatcher->dispatch(new CommentEvent($comment));

        return $comment;
    }
}
