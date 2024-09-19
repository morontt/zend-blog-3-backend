<?php

namespace App\DTO;

class ExternalUserDTO
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string|null
     */
    public $username;

    /**
     * @var string|null
     */
    public $displayName;

    /**
     * @var string|null
     */
    public $firstName;

    /**
     * @var string|null
     */
    public $lastName;

    /**
     * @var string|null
     */
    public $gender;

    /**
     * @var string|null
     */
    public $email;

    /**
     * @var string|null
     */
    public $avatar;

    /**
     * @var string
     */
    public $dataProvider;

    /**
     * @var string
     */
    public $rawData;
}
