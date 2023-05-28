<?php

namespace Mtt\BlogBundle\Telegram\Command;

use Mtt\BlogBundle\Entity\Repository\CommentRepository;
use Mtt\BlogBundle\Event\CommentEvent;
use Mtt\BlogBundle\MttBlogEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Xelbot\Telegram\Command\AbstractAdminCommand;
use Xelbot\Telegram\Command\RequesterTrait;
use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Entity\Message;
use Xelbot\Telegram\Robot;

class DeleteComment extends AbstractAdminCommand implements TelegramCommandInterface
{
    use RequesterTrait;

    /**
     * @var CommentRepository
     */
    private $repository;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param CommentRepository $repository
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(CommentRepository $repository, EventDispatcherInterface $dispatcher)
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    public function getCommandName(): string
    {
        return 'deletecomment';
    }

    /**
     * @param Message $message
     */
    protected function executeCommand(Message $message): void
    {
        $comment = null;
        $matches = [];
        if (preg_match('/^\/deletecomment (\d+)$/', $message->getText(), $matches)) {
            $commentId = (int)$matches[1];
            $comment = $this->repository->find($commentId);
        }

        if ($comment) {
            $this->repository->markAsDeleted($comment);
            $this->dispatcher->dispatch(MttBlogEvents::DELETE_COMMENT, new CommentEvent($comment));

            $this->requester->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => 'Готово ' . Robot::EMOJI_ROBOT,
                'parse_mode' => 'HTML',
            ]);
        } else {
            $this->requester->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => 'Нет такого комментария, хозяин ' . Robot::EMOJI_ROBOT,
                'parse_mode' => 'HTML',
            ]);
        }
    }
}
