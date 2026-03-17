<?php

declare(strict_types=1);

namespace App\DTO;

class WsseTokenDTO extends BaseObject
{
    public string $username;
    public string $digest;
    public string $nonce;
    public string $created;
}
