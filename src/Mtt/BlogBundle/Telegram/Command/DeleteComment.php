<?php

namespace Mtt\BlogBundle\Telegram\Command;

use Mtt\BlogBundle\Entity\Repository\CommentRepository;
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
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
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
