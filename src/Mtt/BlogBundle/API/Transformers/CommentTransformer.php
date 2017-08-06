<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 29.11.14
 * Time: 15:19
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\ViewComment;

class CommentTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'commentator',
    ];

    /**
     * @param Comment|ViewComment $item
     *
     * @return array
     */
    public function transform($item): array
    {
        $commentatorId = null;
        $username = null;
        $email = null;
        $website = null;
        $locationCity = null;
        $locationRegion = null;
        $locationCountry = null;
        $emailHash = null;

        if ($item instanceof Comment) {
            $commentator = $item->getCommentator();
            if ($commentator) {
                $commentatorId = $commentator->getId();
                $username = $commentator->getName();
                $email = $commentator->getMail();
                $website = $commentator->getWebsite();
                $emailHash = $commentator->getEmailHash();
            } else {
                $user = $item->getUser();
                if ($user) {
                    $username = $user->getUsername();
                    $email = $user->getMail();
                    $emailHash = $user->getEmailHash();
                }
            }

            $location = $item->getGeoLocation();
            if ($location) {
                $city = $location->getCity();
                $locationCity = $city->getCity();
                $locationRegion = $city->getRegion();
                $locationCountry = $city->getCountry()->getName();
            }
        } elseif ($item instanceof ViewComment) {
            $commentatorId = $item->getVirtualUserId();

            $username = $item->getUsername();
            $email = $item->getEmail();
            $website = $item->getWebsite();
            $emailHash = $item->getEmailHash();

            $locationCity = $item->getCity();
            $locationRegion = $item->getRegion();
            $locationCountry = $item->getCountry();
        }

        $data = [
            'id' => $item->getId(),
            'text' => $item->getText(),
            'commentator' => $commentatorId,
            'commentatorId' => $commentatorId,
            'username' => $username,
            'email' => $email,
            'website' => $website,
            'emailHash' => $emailHash,
            'ipAddr' => $item->getIpAddress(),
            'disqusId' => (int)$item->getDisqusId(),
            'city' => $locationCity,
            'region' => $locationRegion,
            'country' => $locationCountry,
            'deleted' => $item->isDeleted(),
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
