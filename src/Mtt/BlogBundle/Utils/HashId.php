<?php

namespace Mtt\BlogBundle\Utils;

use Hashids\Hashids;

class HashId
{
    const TYPE_USER = 1;
    const TYPE_COMMENTATOR = 2;
    const MALE = 4;
    const FEMALE = 8;

    /**
     * @param int $id
     * @param int $options
     *
     * @return string
     */
    public static function hash(int $id, int $options): string
    {
        $hashids = new Hashids(
            'Thi5 is sa1t :)',
            6,
            '1234567890ABCDEFGHJKLMNPQRSTUVWXYZ',
        );

        return $hashids->encode($id, $options);
    }
}
