<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 29.11.14
 * Time: 15:19
 */

namespace Mtt\BlogBundle\API\Transformers;

use League\Fractal\TransformerAbstract;
use Mtt\BlogBundle\Entity\Comment;

class CommentTransformer extends TransformerAbstract
{
    /**
     * @param Comment $item
     * @return array
     */
    public function transform(Comment $item)
    {
        $commentator = $item->getCommentator();
        $commentatorId = null;
        if ($commentator) {
            $commentatorId = $commentator->getId();
        }

        $data = [
            'id' => $item->getId(),
            'text' => $item->getText(),
            'commentator_id' => $commentatorId,
            'ip_addr' => $item->getIpAddress(),
            'disqus_id' => (int)$item->getDisqusId(),
            'created_at' => new \DateTime('now'),
        ];

        return $data;
    }
}
