<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 29.11.14
 * Time: 15:19
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Comment;

class CommentTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'Commentator',
    ];

    /**
     * @param Comment $item
     *
     * @return array
     */
    public function transform(Comment $item)
    {
        $commentator = $item->getCommentator();
        $commentatorId = null;
        if ($commentator) {
            $commentatorId = $commentator->getId();
        }

        $location = $item->getGeoLocation();
        if ($location) {
            $city = $location->getCity();
            $locationCity = $city->getCity();
            $locationRegion = $city->getRegion();
            $locationCountry = $city->getCountry()->getName();
        } else {
            $locationCity = null;
            $locationRegion = null;
            $locationCountry = null;
        }

        $data = [
            'id' => $item->getId(),
            'text' => $item->getText(),
            'commentator' => $commentatorId,
            'ipAddr' => $item->getIpAddress(),
            'disqusId' => (int)$item->getDisqusId(),
            'city' => $locationCity,
            'region' => $locationRegion,
            'country' => $locationCountry,
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
        ];

        return $data;
    }

    /**
     * @param Comment $entity
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCommentator(Comment $entity)
    {
        $commentator = $entity->getCommentator();
        $items = [];
        if ($commentator) {
            $items = [$commentator];
        }

        return $this->collection($items, new CommentatorTransformer(), 'commentators');
    }
}
