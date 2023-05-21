<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 21.02.15
 * Time: 21:58
 */

namespace Mtt\BlogBundle\API\Transformers;

use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    /**
     * @param \DateTime|null $dateTime
     *
     * @return string|null
     */
    protected function dateTimeToISO(\DateTime $dateTime = null)
    {
        $result = null;
        if ($dateTime) {
            $result = $dateTime->format(\DateTimeInterface::ATOM);
        }

        return $result;
    }
}
