<?php

namespace App\DTO;

class CategoryDTO extends BaseObject
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $url;

    /**
     * @var int|null
     */
    public $parentId;
}
