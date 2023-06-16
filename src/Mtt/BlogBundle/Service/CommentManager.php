<?php

namespace Mtt\BlogBundle\Service;

use Laminas\Filter\StripTags;
use Mtt\BlogBundle\DTO\CommentDTO;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\Repository\CommentatorRepository;
use Mtt\BlogBundle\Entity\Repository\CommentRepository;
use Mtt\BlogBundle\Entity\Repository\PostRepository;
use Mtt\BlogBundle\Event\CommentEvent;
use Mtt\BlogBundle\Exception\NotAllowedCommentException;
use Mtt\BlogBundle\MttBlogEvents;
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

    public function __construct(
        Tracking $tracking,
        EventDispatcherInterface $dispatcher,
        CommentatorRepository $commentatorRepo,
        CommentRepository $commentRepo,
        PostRepository $postRepo
    ) {
        $this->tracking = $tracking;
        $this->dispatcher = $dispatcher;
        $this->commentatorRepo = $commentatorRepo;
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
        $agent = $this->tracking->getTrackingAgent($commentData->userAgent);

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

        $commentator = $this->commentatorRepo->findOrCreate($commentData->commentator);
        $comment->setCommentator($commentator);

        $this->commentRepo->save($comment);

        $this->dispatcher->dispatch(MttBlogEvents::REPLY_COMMENT, new CommentEvent($comment));

        return $comment;
    }
}
