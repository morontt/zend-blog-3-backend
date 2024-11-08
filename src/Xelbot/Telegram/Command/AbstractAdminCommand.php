<?php

namespace Xelbot\Telegram\Command;

use Xelbot\Telegram\Entity\Message;
use Xelbot\Telegram\Exception\AccessDeniedTelegramException;

abstract class AbstractAdminCommand
{
    /**
     * @var int
     */
    private $adminId;

    /**
     * @param int $adminId
     */
    public function setAdminId(int $adminId)
    {
        $this->adminId = $adminId;
    }

    /**
     * @param Message $message
     */
    abstract protected function executeCommand(Message $message): void;

    /**
     * @param Message $message
     *
     * @throws AccessDeniedTelegramException
     */
    public function execute(Message $message): void
    {
        // TODO Null pointer exception may occur here
        if ($message->getFrom()->getId() != $this->adminId) {
            throw new AccessDeniedTelegramException("Access Denied for user ID:{$message->getFrom()->getId()}");
        }

        $this->executeCommand($message);
    }
}
