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
use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\Tag;

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
     * @param $categories
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
        $resource = new Item($entity, new CategoryTransformer(), 'category');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param $tags
     * @return array
     */
    public function getTagsArray($tags)
    {
        $resource = new Collection($tags, new TagTransformer(), 'tags');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param Tag $tag
     * @return array
     */
    public function getTag(Tag $tag)
    {
        $resource = new Item($tag, new TagTransformer(), 'tag');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param array $data
     * @return array
     */
    public function createTag(array $data)
    {
        return $this->saveTag(new Tag(), $data);
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

    public function createCategory(array $data)
    {
        $category = new Category();

        CategoryTransformer::reverseTransform($category, $data);

        if ($data['parent_id']) {
            $parent = $this->em->getReference('MttBlogBundle:Category', (int)$data['parent_id']);
            $category->setParent($parent);
        }

        $this->em->persist($category);
        $this->em->flush();

        return $this->getCategory($category);
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
        $resource = new Item($entity, new CommentatorTransformer(), 'commentator');

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
