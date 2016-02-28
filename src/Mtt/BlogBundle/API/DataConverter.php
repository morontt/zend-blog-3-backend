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
use Mtt\BlogBundle\API\Transformers\TagTransformer;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Tag;
use Mtt\BlogBundle\Utils\Inflector;

/**
 * Class DataConverter
 * @package Mtt\BlogBundle\API
 *
 * @method array getCategory(Category $entity)
 * @method array getCategoryArray($collection)
 * @method array getComment(Comment $entity)
 * @method array getCommentArray($collection)
 * @method array getCommentator(Commentator $entity)
 * @method array getCommentatorArray($collection)
 * @method array getPost(Post $entity)
 * @method array getPostArray($collection)
 * @method array getTag(Tag $entity)
 * @method array getTagArray($collection)
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
     * @param $method
     * @param $arguments
     * @return array|null
     */
    public function __call($method, $arguments)
    {
        $result = null;
        $matches = [];
        if (preg_match('/^get([A-Z]\w+)Array$/', $method, $matches)) {
            $class = 'Mtt\\BlogBundle\\API\\Transformers\\' . $matches[1] . 'Transformer';
            $resource = new Collection(
                $arguments[0],
                new $class,
                Inflector::pluralize(lcfirst($matches[1]))
            );

            return $this->fractal->createData($resource)->toArray();
        } elseif (preg_match('/^get([A-Z]\w+)$/', $method, $matches)) {
            $class = 'Mtt\\BlogBundle\\API\\Transformers\\' . $matches[1] . 'Transformer';
            $resource = new Item($arguments[0], new $class, lcfirst($matches[1]));

            $result = $this->fractal->createData($resource)->toArray();
        } else {
            throw new \RuntimeException(sprintf('Undefined method: %s', $method));
        }

        return $result;
    }
}
