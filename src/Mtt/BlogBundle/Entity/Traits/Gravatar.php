<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 26.08.17
 * Time: 14:25
 */

namespace Mtt\BlogBundle\Entity\Traits;

use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Utils\HashId;

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

        $userOffset = 10000000;
        if ($id > $userOffset) {
            $id = $id - $userOffset;
            $userType = HashId::TYPE_USER;
        }

        $gender = ($this->getGender() == Commentator::MALE) ? HashId::MALE : HashId::FEMALE;

        return HashId::hash($id, $userType | $gender);
    }
}
