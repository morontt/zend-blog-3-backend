<?php

namespace App\DTO;

class UserDTO extends BaseObject
{
    /** @var string */
    public $username;

    /** @var string|null */
    public $displayName;

    /** @var string */
    public $email;

    /** @var string */
    public $role;

    /** @var bool */
    public $isMale;
}
