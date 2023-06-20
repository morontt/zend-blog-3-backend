<?php

namespace Mtt\BlogBundle\Telegram\Command;

use Mtt\BlogBundle\DTO\CommentDTO;
use Mtt\BlogBundle\DTO\UserDTO;
use Mtt\BlogBundle\Entity\Repository\CommentRepository;
use Mtt\BlogBundle\Service\CommentManager;
use Mtt\BlogBundle\Utils\Http;
use Xelbot\Telegram\Command\AbstractAdminCommand;
use Xelbot\Telegram\Command\TelegramCommandInterface;
use Xelbot\Telegram\Command\TelegramCommandTrait;
use Xelbot\Telegram\Entity\Message;
use Xelbot\Telegram\Robot;

class AnswerComment extends AbstractAdminCommand implements TelegramCommandInterface
{
    use TelegramCommandTrait;

    /**
     * @var CommentRepository
     */
    private $repository;

    /**
     * @var CommentManager
     */
    private $commentManager;

    public function __construct(CommentRepository $repository, CommentManager $commentManager)
    {
        $this->repository = $repository;
        $this->commentManager = $commentManager;
    }

    public function getCommandName(): string
    {
        return 'answer';
    }

    protected function executeCommand(Message $message): void
    {
        $comment = null;
        $matches = [];
        if (preg_match('/^\/answer (\d+)\s+(.+)$/ms', $message->getText(), $matches)) {
            $commentId = (int)$matches[1];
            $comment = $this->repository->find($commentId);
        }

        if ($comment) {
            $commentData = new CommentDTO();
            $commentData->topicId = $comment->getPost()->getId();
            $commentData->parentId = $comment->getId();
            $commentData->text = trim($matches[2]);

            $commentData->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $commentData->ipAddress = Http::getClientIp();

            $user = new UserDTO();
            $user->id = 1;
            $commentData->user = $user;

            $this->commentManager->saveExternalComment($commentData);

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
