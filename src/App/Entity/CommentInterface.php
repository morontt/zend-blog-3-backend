<?php

namespace App\Entity;

interface CommentInterface
{
    public function getId(): ?int;

    public function getText();

    public function getIpAddress();

    public function isDeleted();

    public function getTimeCreated();
}
