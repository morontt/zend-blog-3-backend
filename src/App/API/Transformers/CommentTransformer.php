<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 29.11.14
 * Time: 15:19
 */

namespace App\API\Transformers;

use League\Fractal\Resource\Collection;
use App\Entity\Comment;
use App\Entity\CommentInterface;
use App\Entity\ViewComment;
use App\Entity\ViewCommentator;
use App\Utils\EmojiFlagSymbol;

class CommentTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'commentator',
    ];

    /**
     * @param CommentInterface $item
     *
     * @return array
     */
    public function transform(CommentInterface $item): array
    {
        $commentatorId = null;
        $username = null;
        $email = null;
        $website = null;
        $locationCity = null;
        $locationRegion = null;
        $locationCountry = null;
        $imageHash = null;
        $countryCode = null;

        $userAgent = null;
        $bot = false;

        if ($item instanceof Comment) {
            $commentator = $item->getCommentator();
            if ($commentator) {
                $commentatorId = $commentator->getId();
                $username = $commentator->getName();
                $email = $commentator->getEmail();
                $website = $commentator->getWebsite();
            } else {
                $user = $item->getUser();
                if ($user) {
                    $commentatorId = ViewCommentator::USER_ID_OFFSET + $user->getId();
                    $username = $user->getUsername();
                    $email = $user->getEmail();
                }
            }

            $imageHash = $item->getAvatarHash();
            $location = $item->getGeoLocation();
            if ($location && $city = $location->getCity()) {
                $locationCity = $city->getCity();
                $locationRegion = $city->getRegion();
                $locationCountry = $city->getCountry()->getName();
                $countryCode = $city->getCountry()->getCode();
            }

            $trackingAgent = $item->getTrackingAgent();
            if ($trackingAgent) {
                $userAgent = $trackingAgent->getUserAgent();
                $bot = $trackingAgent->isBot();
            }
        } elseif ($item instanceof ViewComment) {
            $commentatorId = $item->getVirtualUserId();

            $username = $item->getUsername();
            $email = $item->getEmail();
            $website = $item->getWebsite();
            $imageHash = $item->getAvatarHash();

            $locationCity = $item->getCity();
            $locationRegion = $item->getRegion();
            $locationCountry = $item->getCountry();
            $countryCode = $item->getCode();

            $userAgent = $item->getUserAgent();
            $bot = $item->isBot();
        }

        $parentId = null;
        $parent = $item->getParent();
        if ($parent) {
            $parentId = $parent->getId();
        }

        try {
            $flag = $countryCode ? EmojiFlagSymbol::get($countryCode) : '';
        } catch (\Exception $e) {
            $flag = '';
        }

        return [
            'id' => $item->getId(),
            'text' => $item->getText(),
            'commentator' => $commentatorId,
            'commentatorId' => $commentatorId,
            'username' => $username,
            'email' => $email,
            'website' => $website,
            'ipAddr' => $item->getIpAddress(),
            'city' => $locationCity,
            'region' => $locationRegion,
            'country' => $locationCountry,
            'countryFlag' => $flag,
            'parent' => $parentId,
            'imageHash' => $imageHash,
            'deleted' => $item->isDeleted(),
            'userAgent' => $userAgent,
            'bot' => $bot,
            'createdAt' => $this->dateTimeToISO($item->getTimeCreated()),
        ];
    }

    /**
     * @param Comment $entity
     * @param array $data
     */
    public static function reverseTransform(Comment $entity, array $data)
    {
        $entity->setText($data['text']);
    }

    /**
     * @param Comment $entity
     *
     * @return Collection
     */
    public function includeCommentator(Comment $entity): Collection
    {
        $commentator = $entity->getCommentator();
        $items = [];
        if ($commentator) {
            $items = [$commentator];
        }

        return $this->collection($items, new CommentatorTransformer(), 'commentators');
    }
}
