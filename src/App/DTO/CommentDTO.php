<?php

namespace App\DTO;

class CommentDTO
{
    /**
     * @var string
     */
    public $text;

    /**
     * @var CommentatorDTO|null
     */
    public $commentator;

    /**
     * @var CommentUserDTO|null
     */
    public $user;

    /**
     * @var string|null
     */
    public $userAgent = '';

    /**
     * @var string|null
     */
    public $ipAddress;

    /**
     * @var int
     */
    public $topicId;

    /**
     * @var int
     */
    public $parentId = 0;

    /**
     * @var string|null
     */
    public $forceCreatedAt;

    /**
     * @var bool
     */
    public $deleted = false;
}
