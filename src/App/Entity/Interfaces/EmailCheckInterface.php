<?php

namespace App\Entity\Interfaces;

use DateTime;

interface EmailCheckInterface
{
    /**
     * @return string|null
     */
    public function getEmail();

    public function setFakeEmail(?bool $fakeEmail): static;

    public function setEmailCheck(?DateTime $emailCheck): static;
}
