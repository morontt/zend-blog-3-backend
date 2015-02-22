<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 12:33
 */

namespace Mtt\BlogBundle\API;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use Mtt\BlogBundle\API\Transformers\CategoryTransformer;
use Mtt\BlogBundle\API\Transformers\CommentTransformer;
use Mtt\BlogBundle\API\Transformers\CommentatorTransformer;
use Mtt\BlogBundle\API\Transformers\PostTransformer;
use Mtt\BlogBundle\API\Transformers\TagTransformer;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Entity\Commentator;

class DataConverter
{
    /**
     * @var Manager
     */
    protected $fractal;


    public function __construct()
    {
        $this->fractal = new Manager();
        $this->fractal->setSerializer(new JsonApiSerializer());
    }

    /**
     * @param array $categories
     * @return array
     */
    public function getCategoryArray($categories)
    {
        $resource = new Collection($categories, new CategoryTransformer(), 'categories');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param Category $entity
     * @return array
     */
    public function getCategory(Category $entity)
    {
        $resource = new Item($entity, new CategoryTransformer(), 'categories');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param array $tags
     * @return array
     */
    public function getTagsArray($tags)
    {
        $resource = new Collection($tags, new TagTransformer(), 'tags');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param array $commentators
     * @return array
     */
    public function getCommentatorsArray($commentators)
    {
        $resource = new Collection($commentators, new CommentatorTransformer(), 'commentators');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param Commentator $entity
     * @return array
     */
    public function getCommentator(Commentator $entity)
    {
        $resource = new Item($entity, new CommentatorTransformer(), 'commentators');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param array $comments
     * @return array
     */
    public function getCommentsArray($comments)
    {
        $resource = new Collection($comments, new CommentTransformer(), 'comments');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param array $posts
     * @return array
     */
    public function getPostsArray($posts)
    {
        $resource = new Collection($posts, new PostTransformer(), 'posts');

        return $this->fractal->createData($resource)->toArray();
    }
}
