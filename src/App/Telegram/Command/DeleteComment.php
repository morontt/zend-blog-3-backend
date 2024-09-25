<?php

namespace App\Telegram\Command;

use App\Event\CommentEvent;
use App\Events;
use App\Repository\CommentRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Xelbot\Telegram\Command\AbstractAdminCommand;
use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Command\TelegramCommandTrait;
use Xelbot\Telegram\Entity\Message;
use Xelbot\Telegram\Robot;

class DeleteComment extends AbstractAdminCommand implements TelegramCommandInterface
{
    use TelegramCommandTrait;

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
            $this->dispatcher->dispatch(Events::DELETE_COMMENT, new CommentEvent($comment));

            //TODO Null pointer exception may occur here
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
