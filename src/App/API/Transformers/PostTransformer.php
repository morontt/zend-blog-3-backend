<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 19:36
 */

namespace App\API\Transformers;

use App\DTO\ArticleDTO;
use App\Entity\Post;
use App\Entity\Tag;
use App\Utils\RuTransform;
use DateTime;
use Exception;
use League\Fractal\Resource\ResourceInterface;

class PostTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $availableIncludes = [
        'category',
    ];

    /**
     * @param Post $item
     *
     * @return array
     */
    public function transform(Post $item)
    {
        return [
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'url' => $item->getUrl(),
            'category' => $item->getCategory()->getId(),
            'categoryId' => $item->getCategory()->getId(),
            'hidden' => $item->isHide(),
            'disableComments' => $item->isDisableComments(),
            'forceCreatedAt' => $item->getForceCreatedAt() ? $item->getForceCreatedAt()->format('Y-m-d H:i:s.v') : null,
            'text' => $item->getRawText(),
            'description' => $item->getDescription(),
            'tagsString' => implode(
                ', ',
                $item->getTags()->map(
                    function (Tag $tag) {
                        return $tag->getName();
                    }
                )->toArray()
            ),
            'timeCreated' => $this->dateTimeToISO($item->getTimeCreated()),
            'lastUpdate' => $this->dateTimeToISO($item->getLastUpdate()),
        ];
    }

    /**
     * @param Post $entity
     * @param ArticleDTO $data
     */
    public static function reverseTransform(Post $entity, ArticleDTO $data)
    {
        if (empty($data['title'])) {
            $data['title'] = 'no subject';
        }

        $entity
            ->setTitle($data['title'])
            ->setHide($data['hidden'])
            ->setDisableComments($data['disableComments'])
            ->setRawText($data['text'])
            ->setDescription($data['description'])
        ;

        $dtForce = null;
        if ($data->forceCreatedAt) {
            try {
                $dtForce = new DateTime($data->forceCreatedAt);
            } catch (Exception $e) {
            }
        }
        $entity->setForceCreatedAt($dtForce);

        if ($data['url']) {
            $entity->setUrl($data['url']);
        } else {
            $entity->setUrl(RuTransform::ruTransform($data['title']));
        }
    }

    /**
     * @param Post $entity
     *
     * @return ResourceInterface
     */
    public function includeCategory(Post $entity): ResourceInterface
    {
        return $this->collection([$entity->getCategory()], new CategoryTransformer(), 'categories');
    }
}
