<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 14:25
 */

namespace Mtt\BlogBundle\Entity\Traits;

use Mtt\BlogBundle\Entity\ViewCommentator;
use Mtt\BlogBundle\Utils\HashId;
use Mtt\UserBundle\Entity\User;

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

        return HashId::hash($id, $userType | $gender);
    }
}
