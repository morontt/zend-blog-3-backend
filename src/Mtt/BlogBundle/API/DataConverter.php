<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 12:33
 */

namespace Mtt\BlogBundle\API;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Mtt\BlogBundle\API\Transformers\CategoryTransformer;
use Mtt\BlogBundle\API\Transformers\CommentatorTransformer;
use Mtt\BlogBundle\API\Transformers\CommentTransformer;
use Mtt\BlogBundle\API\Transformers\MediaFileTransformer;
use Mtt\BlogBundle\API\Transformers\PostTransformer;
use Mtt\BlogBundle\API\Transformers\PygmentsCodeTransformer;
use Mtt\BlogBundle\API\Transformers\PygmentsLanguageTransformer;
use Mtt\BlogBundle\API\Transformers\TagTransformer;
use Mtt\BlogBundle\DTO\PygmentsCodeDTO;
use Mtt\BlogBundle\Entity\Category;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\CommentatorInterface;
use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Entity\Post;
use Mtt\BlogBundle\Entity\PygmentsCode;
use Mtt\BlogBundle\Entity\PygmentsLanguage;
use Mtt\BlogBundle\Entity\Repository\CategoryRepository;
use Mtt\BlogBundle\Entity\Repository\CommentRepository;
use Mtt\BlogBundle\Entity\Tag;
use Mtt\BlogBundle\Model\Image;
use Mtt\BlogBundle\Service\TextProcessor;
use Mtt\BlogBundle\Utils\Inflector;
use Mtt\BlogBundle\Utils\Pygment;
use Mtt\BlogBundle\Utils\RuTransform;

/**
 * Class DataConverter
 *
 * @method array getCategory(Category $entity, $includes = null)
 * @method array getCategoryArray($collection, $includes = null)
 * @method array getComment(Comment $entity, $includes = null)
 * @method array getCommentArray($collection, $includes = null)
 * @method array getCommentator(CommentatorInterface $entity, $includes = null)
 * @method array getCommentatorArray($collection, $includes = null)
 * @method array getMediaFile(Image $entity, $includes = null)
 * @method array getMediaFileArray($collection, $includes = null)
 * @method array getPost(Post $entity, $includes = null)
 * @method array getPostArray($collection, $includes = null)
 * @method array getTag(Tag $entity, $includes = null)
 * @method array getTagArray($collection, $includes = null)
 * @method array getPygmentsLanguage(PygmentsLanguage $entity, $includes = null)
 * @method array getPygmentsLanguageArray($collection, $includes = null)
 * @method array getPygmentsCode(PygmentsCode $entity, $includes = null)
 * @method array getPygmentsCodeArray($collection, $includes = null)
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
     * @var TextProcessor
     */
    protected $textProcessor;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var CommentRepository
     */
    private $commentsRepository;

    /**
     * @param EntityManagerInterface $em
     * @param TextProcessor $textProcessor
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        TextProcessor $textProcessor,
        CommentRepository $commentsRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->fractal = new Manager();
        $this->fractal->setSerializer(new Serializer());
        $this->categoryRepository = $categoryRepository;
        $this->commentsRepository = $commentsRepository;

        $this->em = $em;
        $this->textProcessor = $textProcessor;
    }

    /**
     * @param Tag $entity
     * @param array $data
     *
     * @return array
     */
    public function saveTag(Tag $entity, array $data): array
    {
        TagTransformer::reverseTransform($entity, $data);

        $this->save($entity);

        return $this->getTag($entity);
    }

    /**
     * @param Category $entity
     * @param array $data
     *
     * @throws ORMException
     *
     * @return array
     */
    public function saveCategory(Category $entity, array $data): array
    {
        CategoryTransformer::reverseTransform($entity, $data);

        if ($data['parentId']) {
            $parent = $this->categoryRepository->find((int)$data['parentId']);
            $entity->setParent($parent);
        } else {
            $entity->setParent(null);
        }

        $this->categoryRepository->save($entity);

        return $this->getCategory($entity);
    }

    /**
     * @param Commentator $entity
     * @param array $data
     *
     * @return array
     */
    public function saveCommentator(Commentator $entity, array $data): array
    {
        CommentatorTransformer::reverseTransform($entity, $data);

        $this->save($entity);

        return $this->getCommentator($entity);
    }

    /**
     * @param Comment $entity
     * @param array $data
     *
     * @return array
     */
    public function saveComment(Comment $entity, array $data): array
    {
        CommentTransformer::reverseTransform($entity, $data);
        $this->commentsRepository->save($entity);

        return $this->getComment($entity);
    }

    /**
     * @param Post $entity
     * @param array $data
     *
     * @throws ORMException
     *
     * @return array
     */
    public function savePost(Post $entity, array $data): array
    {
        PostTransformer::reverseTransform($entity, $data);

        $this->textProcessor->processing($entity);

        $entity->setCategory($this->em->getReference('MttBlogBundle:Category', (int)$data['categoryId']));

        if ($entity->isHide()) {
            $entity->setTimeCreated(new DateTime());
        }

        $originalTags = new ArrayCollection();
        foreach ($entity->getTags() as $tag) {
            $originalTags->add($tag);
        }

        $tagsArray = array_map('trim', explode(',', $data['tagsString']));
        foreach ($tagsArray as $tagName) {
            if ($tagName) {
                $tag = $this->em->getRepository('MttBlogBundle:Tag')->getTagForPost($tagName);
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
     * @param MediaFile $entity
     * @param array $data
     *
     * @return array
     */
    public function saveMediaFile(MediaFile $entity, array $data): array
    {
        MediaFileTransformer::reverseTransform($entity, $data);

        if ($data['postId']) {
            $post = $this->em->getRepository('MttBlogBundle:Post')->find((int)$data['postId']);
            if ($post) {
                $entity->setPost($post);
                if ($this->em->getRepository('MttBlogBundle:MediaFile')->getCountByPostId((int)$data['postId']) == 0) {
                    $entity->setDefaultImage(true);
                }
            }
        } else {
            $entity->setPost(null);
        }

        $this->save($entity);

        return $this->getMediaFile(new Image($entity));
    }

    /**
     * @param PygmentsLanguage $entity
     * @param array $data
     *
     * @return array
     */
    public function savePygmentsLanguage(PygmentsLanguage $entity, array $data): array
    {
        PygmentsLanguageTransformer::reverseTransform($entity, $data);

        $this->save($entity);

        return $this->getPygmentsLanguage($entity);
    }

    /**
     * @param PygmentsCode $entity
     * @param PygmentsCodeDTO $data
     *
     * @return array
     */
    public function savePygmentsCode(PygmentsCode $entity, PygmentsCodeDTO $data): array
    {
        $oldHash = $entity->getContentHash();
        PygmentsCodeTransformer::reverseTransform($entity, $data);

        if ($data['languageId']) {
            $entity->setLanguage(
                $this->em->getReference('MttBlogBundle:PygmentsLanguage', (int)$data['languageId'])
            );
        } else {
            $entity->setLanguage(null);
        }

        if ($oldHash != $entity->getContentHash()) {
            $htmlObj = Pygment::highlight($entity->getSourceCode(), $entity->getLexer());
            $entity
                ->setSourceHtml($htmlObj->html())
                ->setSourceHtmlPreview($htmlObj->htmlPreview())
            ;
        }

        $this->save($entity);

        return $this->getPygmentsCode($entity);
    }

    /**
     * @param $method
     * @param $arguments
     *
     * @return array|null
     */
    public function __call($method, $arguments)
    {
        $matches = [];
        if (preg_match('/^get([A-Z]\w+)Array$/', $method, $matches)) {
            $class = 'Mtt\\BlogBundle\\API\\Transformers\\' . $matches[1] . 'Transformer';
            $resource = new Collection(
                $arguments[0],
                new $class(),
                Inflector::pluralize(lcfirst($matches[1]))
            );

            if (!empty($arguments[1])) {
                $this->fractal->parseIncludes($arguments[1]);
            }

            $result = $this->fractal->createData($resource)->toArray();
        } elseif (preg_match('/^get([A-Z]\w+)$/', $method, $matches)) {
            $class = 'Mtt\\BlogBundle\\API\\Transformers\\' . $matches[1] . 'Transformer';
            $resource = new Item($arguments[0], new $class(), lcfirst($matches[1]));

            if (!empty($arguments[1])) {
                $this->fractal->parseIncludes($arguments[1]);
            }

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
