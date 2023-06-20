<?php

namespace Xelbot\Telegram;

use Xelbot\Telegram\Entity\Update;

interface UpdatesManagerInterface
{
    public function saveUpdate(Update $obj, array $requestData);
}
