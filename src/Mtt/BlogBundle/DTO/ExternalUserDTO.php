<?php

namespace Mtt\BlogBundle\DTO;

class ExternalUserDTO
{
    public string $id;
    public ?string $username = null;
    public ?string $displayName = null;
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $gender = null;
    public ?string $email = null;
    public ?string $avatar = null;
    public string $dataProvider;
    public string $rawData;
}
