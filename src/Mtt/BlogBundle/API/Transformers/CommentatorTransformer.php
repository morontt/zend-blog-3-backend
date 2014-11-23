<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 23.11.14
 * Time: 12:22
 */

namespace Mtt\BlogBundle\API\Transformers;

use League\Fractal\TransformerAbstract;
use Mtt\BlogBundle\Entity\Commentator;

class CommentatorTransformer extends TransformerAbstract
{
    /**
     * @param Commentator $item
     * @return array
     */
    public function transform(Commentator $item)
    {
        $data = [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'email' => $item->getMail(),
            'website' => $item->getWebsite(),
            'disqus_id' => (int)$item->getDisqusId(),
            'email_hash' => $item->getEmailHash(),
        ];

        return $data;
    }
}
