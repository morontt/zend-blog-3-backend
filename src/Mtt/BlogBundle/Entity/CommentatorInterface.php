<?php

namespace Mtt\BlogBundle\Entity;

interface CommentatorInterface
{
    /**
     * @return null|int
     */
    public function getId(): ? int;

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return null|string
     */
    public function getEmail(): ? string;

    /**
     * @return null|string
     */
    public function getWebsite() : ? string;

    /**
     * @return null|int
     */
    public function getDisqusId() : ? int;

    /**
     * @return null|string
     */
    public function getEmailHash() : ? string;

    /**
     * @return string
     */
    public function getAvatarHash() : string;
}
