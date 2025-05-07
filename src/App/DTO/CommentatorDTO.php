<?php

namespace App\DTO;

use Laminas\Filter\UriNormalize;

class CommentatorDTO
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $email;

    /**
     * @var string|null
     */
    public $website;

    /**
     * @var int|null
     */
    public $id;

    /**
     * @return string|null
     */
    public function getNormalizedURL(): ?string
    {
        return (new UriNormalize(['enforcedScheme' => 'http']))->filter($this->website);
    }
}
