<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 12:33
 */

namespace Mtt\BlogBundle\API;

use Doctrine\ORM\EntityManager;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Mtt\BlogBundle\API\Transformers\CategoryTransformer;
use Mtt\BlogBundle\API\Transformers\CommentTransformer;
use Mtt\BlogBundle\API\Transformers\CommentatorTransformer;
use Mtt\BlogBundle\API\Transformers\PostTransformer;
use Mtt\BlogBundle\API\Transformers\TagTransformer;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Tag;

/**
 * Class DataConverter
 * @package Mtt\BlogBundle\API
 *
 * @method array getCategory(Category $entity)
 * @method array getComment(Comment $entity)
 * @method array getCommentator(Commentator $entity)
 * @method array getPost(Post $entity)
 * @method array getTag(Tag $entity)
 */
class DataConverter
{
    /**
     * @var Manager
     */
    protected $fractal;

    /**
     * @var EntityManager
     */
    protected $em;


    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->fractal = new Manager();
        $this->fractal->setSerializer(new Serializer());

        $this->em = $em;
    }

    /**
     * @param mixed $categories
     * @return array
     */
    public function getCategoryArray($categories)
    {
        $resource = new Collection($categories, new CategoryTransformer(), 'categories');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param mixed $tags
     * @return array
     */
    public function getTagsArray($tags)
    {
        $resource = new Collection($tags, new TagTransformer(), 'tags');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param Tag $tag
     * @param array $data
     * @return array
     */
    public function saveTag(Tag $tag, array $data)
    {
        TagTransformer::reverseTransform($tag, $data);

        $this->em->persist($tag);
        $this->em->flush();

        return $this->getTag($tag);
    }

    /**
     * @param Category $category
     * @param array $data
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveCategory(Category $category, array $data)
    {
        CategoryTransformer::reverseTransform($category, $data);

        if ($data['parentId']) {
            $parent = $this->em->getReference('MttBlogBundle:Category', (int)$data['parentId']);
            $category->setParent($parent);
        } else {
            $category->setParent(null);
        }

        $this->em->persist($category);
        $this->em->flush();

        return $this->getCategory($category);
    }

    /**
     * @param mixed $commentators
     * @return array
     */
    public function getCommentatorsArray($commentators)
    {
        $resource = new Collection($commentators, new CommentatorTransformer(), 'commentators');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param mixed $comments
     * @return array
     */
    public function getCommentsArray($comments)
    {
        $resource = new Collection($comments, new CommentTransformer(), 'comments');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param mixed $posts
     * @return array
     */
    public function getPostsArray($posts)
    {
        $resource = new Collection($posts, new PostTransformer(), 'posts');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param $method
     * @param $arguments
     * @return array|null
     */
    public function __call($method, $arguments)
    {
        $result = null;
        $matches = [];
        if (preg_match('/^get([A-Z]\w+)$/', $method, $matches)) {
            $class = 'Mtt\\BlogBundle\\API\\Transformers\\' . $matches[1] . 'Transformer';
            $resource = new Item($arguments[0], new $class, lcfirst($matches[1]));

            $result = $this->fractal->createData($resource)->toArray();
        } else {
            throw new \RuntimeException(sprintf('Undefined method: %s', $method));
        }

        return $result;
    }
}
