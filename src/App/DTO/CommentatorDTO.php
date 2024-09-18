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
     * @return string|null
     */
    public function getNormalizedURL(): ?string
    {
        $filter = new UriNormalize(['enforcedScheme' => 'http']);

        return $filter->filter($this->website);
    }
}
