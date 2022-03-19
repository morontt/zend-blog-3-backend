<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 29.11.14
 * Time: 15:19
 */

namespace Mtt\BlogBundle\API\Transformers;

use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\CommentInterface;
use Mtt\BlogBundle\Entity\ViewComment;
use Mtt\BlogBundle\Utils\EmojiFlagSymbol;

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
        $emailHash = null;
        $countryCode = null;

        if ($item instanceof Comment) {
            $commentator = $item->getCommentator();
            if ($commentator) {
                $commentatorId = $commentator->getId();
                $username = $commentator->getName();
                $email = $commentator->getEmail();
                $website = $commentator->getWebsite();
                $emailHash = $commentator->getAvatarHash();
            } else {
                $user = $item->getUser();
                if ($user) {
                    $username = $user->getUsername();
                    $email = $user->getEmail();
                    $emailHash = $user->getEmailHash();
                }
            }

            $location = $item->getGeoLocation();
            if ($location) {
                $city = $location->getCity();
                $locationCity = $city->getCity();
                $locationRegion = $city->getRegion();
                $locationCountry = $city->getCountry()->getName();
                $countryCode = $city->getCountry()->getCode();
            }
        } elseif ($item instanceof ViewComment) {
            $commentatorId = $item->getVirtualUserId();

            $username = $item->getUsername();
            $email = $item->getEmail();
            $website = $item->getWebsite();
            $emailHash = $item->getAvatarHash();

            $locationCity = $item->getCity();
            $locationRegion = $item->getRegion();
            $locationCountry = $item->getCountry();
            $countryCode = $item->getCode();
        }

        $parentId = null;
        $parent = $item->getParent();
        if ($parent) {
            $parentId = $parent->getId();
        }

        try {
            $flag = EmojiFlagSymbol::get($countryCode);
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
            'emailHash' => $emailHash,
            'ipAddr' => $item->getIpAddress(),
            'city' => $locationCity,
            'region' => $locationRegion,
            'country' => $locationCountry,
            'countryCode' => $flag,
            'parent' => $parentId,
            'deleted' => $item->isDeleted(),
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
