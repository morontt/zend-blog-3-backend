<?php

namespace App\Telegram\Command;

use App\DTO\CommentDTO;
use App\DTO\CommentUserDTO;
use App\Repository\CommentRepository;
use App\Service\CommentManager;
use App\Utils\Http;
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

            $user = new CommentUserDTO();
            $user->id = 1;
            $commentData->user = $user;

            $this->commentManager->saveExternalComment($commentData);

            #TODO Null pointer exception may occur here
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
