<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 12:33
 */

namespace Mtt\BlogBundle\API;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Mtt\BlogBundle\API\Transformers\CategoryTransformer;
use Mtt\BlogBundle\API\Transformers\PostTransformer;
use Mtt\BlogBundle\API\Transformers\TagTransformer;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\Tag;
use Mtt\BlogBundle\Utils\Inflector;
use Mtt\BlogBundle\Utils\RuTransform;

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
     * @param Tag $entity
     * @param array $data
     * @return array
     */
    public function saveTag(Tag $entity, array $data)
    {
        TagTransformer::reverseTransform($entity, $data);

        $this->save($entity);

        return $this->getTag($entity);
    }

    /**
     * @param Category $entity
     * @param array $data
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveCategory(Category $entity, array $data)
    {
        CategoryTransformer::reverseTransform($entity, $data);

        if ($data['parentId']) {
            $parent = $this->em->getReference('MttBlogBundle:Category', (int)$data['parentId']);
            $entity->setParent($parent);
        } else {
            $entity->setParent(null);
        }

        $this->save($entity);

        return $this->getCategory($entity);
    }

    /**
     * @param Post $entity
     * @param array $data
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function savePost(Post $entity, array $data)
    {
        PostTransformer::reverseTransform($entity, $data);

        $entity->setCategory($this->em->getReference('MttBlogBundle:Category', (int)$data['categoryId']));

        $now = new \DateTime();
        $entity->setLastUpdate($now);

        if ($entity->isHide()) {
            $entity->setTimeCreated($now);
        }

        $originalTags = new ArrayCollection();
        foreach ($entity->getTags() as $tag) {
            $originalTags->add($tag);
        }

        $tagsArray = array_map('trim', explode(',', $data['tagsString']));
        foreach ($tagsArray as $tagName) {
            if ($tagName) {
                $tag = $this->em->getRepository('MttBlogBundle:Tag')->findOneBy(['name' => $tagName]);
                if ($tag) {
                    if ($originalTags->contains($tag)) {
                        $originalTags->removeElement($tag);
                    } else {
                        $entity->addTag($tag);
                    }
                } else {
                    $tag = new Tag();
                    $tag
                        ->setName($tagName)
                        ->setUrl(RuTransform::ruTransform($tagName))
                    ;
                    $entity->addTag($tag);
                }
                $this->em->persist($tag);
            }
        }

        foreach ($originalTags as $tag) {
            $entity->removeTag($tag);
        }

        $this->save($entity);

        return $this->getPost($entity);
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

    /**
     * @param $entity
     */
    protected function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
