<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 14:25
 */

namespace App\Entity\Traits;

use App\Entity\User;
use App\Entity\ViewCommentator;
use App\Utils\HashId;

trait Gravatar
{
    /**
     * @return string
     */
    public function getAvatarHash(): string
    {
        $userType = HashId::TYPE_COMMENTATOR;

        if (method_exists($this, 'getVirtualUserId')) {
            $id = $this->getVirtualUserId();
        } else {
            $id = $this->getId();
        }

        if ($id > ViewCommentator::USER_ID_OFFSET) {
            $id -= ViewCommentator::USER_ID_OFFSET;
            $userType = HashId::TYPE_USER;
        }

        $gender = ($this->getGender() === User::MALE) ? HashId::MALE : HashId::FEMALE;
        $options = $userType | $gender;

        if (method_exists($this, 'getAvatarVariant')) {
            $options += $this->getAvatarVariant() << 4;
        }

        return HashId::hash($id, $options);
    }
}
