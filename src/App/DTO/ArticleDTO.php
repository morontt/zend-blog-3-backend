<?php

namespace App\DTO;

class ArticleDTO extends BaseObject
{
    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string|null
     */
    public $url;

    /**
     * @var int
     */
    public $categoryId;

    /**
     * @var bool
     */
    public $hidden;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var string|null
     */
    public $tagsString;

    /**
     * @var bool
     */
    public $disableComments;
}
