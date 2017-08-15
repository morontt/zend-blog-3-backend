<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 14:25
 */

namespace Mtt\BlogBundle\Entity\Traits;

trait Gravatar
{
    /**
     * @return string
     */
    public function getAvatarHash(): string
    {
        if ($this->getEmail()) {
            $hash = md5(strtolower(trim($this->getEmail())));
        } else {
            $hash = md5(strtolower(trim($this->getName())));
            if ($this->getWebsite()) {
                $hash = md5($hash . strtolower(trim($this->getWebsite())));
            }
        }

        return $hash;
    }
}
