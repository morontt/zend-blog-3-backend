<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 12:33
 */

namespace App\API;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use App\DTO\ArticleDTO;
use App\DTO\CategoryDTO;
use App\DTO\PygmentsCodeDTO;
use App\Entity;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Model\Image;
use App\Service\TextProcessor;
use App\Utils\Inflector;
use App\Utils\Pygment;
use App\Utils\RuTransform;

/**
 * Class DataConverter
 *
 * @method array getCategory(Entity\Category $entity, $includes = null)
 * @method array getCategoryArray($collection, $includes = null)
 * @method array getComment(Entity\Comment $entity, $includes = null)
 * @method array getCommentArray($collection, $includes = null)
 * @method array getCommentator(Entity\CommentatorInterface $entity, $includes = null)
 * @method array getCommentatorArray($collection, $includes = null)
 * @method array getMediaFile(Image $entity, $includes = null)
 * @method array getMediaFileArray($collection, $includes = null)
 * @method array getPost(Entity\Post $entity, $includes = null)
 * @method array getPostArray($collection, $includes = null)
 * @method array getTag(Entity\Tag $entity, $includes = null)
 * @method array getTagArray($collection, $includes = null)
 * @method array getPygmentsLanguage(Entity\PygmentsLanguage $entity, $includes = null)
 * @method array getPygmentsLanguageArray($collection, $includes = null)
 * @method array getPygmentsCode(Entity\PygmentsCode $entity, $includes = null)
 * @method array getPygmentsCodeArray($collection, $includes = null)
 * @method array getUserAgent(Entity\TrackingAgent $entity, $includes = null)
 * @method array getUserAgentArray($collection, $includes = null)
 * @method array getTracking(Entity\Tracking $entity, $includes = null)
 * @method array getTrackingArray($collection, $includes = null)
 * @method array getTelegramUser(Entity\TelegramUser $entity, $includes = null)
 * @method array getTelegramUserArray($collection, $includes = null)
 * @method array getTelegramUpdate(Entity\TelegramUpdate $entity, $includes = null)
 * @method array getTelegramUpdateArray($collection, $includes = null)
 * @method array getUser(Entity\User $entity, $includes = null)
 * @method array getUserArray($collection, $includes = null)
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
     * @param CommentRepository $commentsRepository
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
     * @param Entity\Category $entity
     * @param CategoryDTO $data
     *
     * @return array
     */
    public function saveCategory(Entity\Category $entity, CategoryDTO $data): array
    {
        Transformers\CategoryTransformer::reverseTransform($entity, $data);

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
     * @param Entity\Commentator $entity
     * @param array $data
     *
     * @return array
     */
    public function saveCommentator(Entity\Commentator $entity, array $data): array
    {
        Transformers\CommentatorTransformer::reverseTransform($entity, $data);

        $this->save($entity);

        return $this->getCommentator($entity);
    }

    /**
     * @param Entity\TrackingAgent $entity
     * @param array $data
     *
     * @return array
     */
    public function saveTrackingAgent(Entity\TrackingAgent $entity, array $data): array
    {
        Transformers\UserAgentTransformer::reverseTransform($entity, $data);

        $this->save($entity);

        return $this->getUserAgent($entity);
    }

    /**
     * @param Entity\Comment $entity
     * @param array $data
     *
     * @return array
     */
    public function saveComment(Entity\Comment $entity, array $data): array
    {
        Transformers\CommentTransformer::reverseTransform($entity, $data);
        $this->commentsRepository->save($entity);

        return $this->getComment($entity);
    }

    /**
     * @param Entity\Post $entity
     * @param ArticleDTO $data
     *
     * @throws ORMException
     *
     * @return array
     */
    public function savePost(Entity\Post $entity, ArticleDTO $data): array
    {
        Transformers\PostTransformer::reverseTransform($entity, $data);

        $this->textProcessor->processing($entity);

        $entity->setCategory($this->em->getReference(Entity\Category::class, (int)$data['categoryId']));

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
                $tag = $this->em->getRepository(Entity\Tag::class)->getTagForPost($tagName);
                if ($tag) {
                    if ($originalTags->contains($tag)) {
                        $originalTags->removeElement($tag);
                    } else {
                        $entity->addTag($tag);
                    }
                } else {
                    $tag = new Entity\Tag();
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
     * @param Entity\MediaFile $entity
     * @param array $data
     *
     * @return array
     */
    public function saveMediaFile(Entity\MediaFile $entity, array $data): array
    {
        Transformers\MediaFileTransformer::reverseTransform($entity, $data);

        if ($data['postId']) {
            $post = $this->em->getRepository(Entity\Post::class)->find((int)$data['postId']);
            if ($post) {
                $entity->setPost($post);
                if ($this->em->getRepository(Entity\MediaFile::class)->getCountByPostId((int)$data['postId']) === 0) {
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
     * @param Entity\PygmentsCode $entity
     * @param PygmentsCodeDTO $data
     *
     * @throws ORMException
     *
     * @return array
     */
    public function savePygmentsCode(Entity\PygmentsCode $entity, PygmentsCodeDTO $data): array
    {
        $oldHash = $entity->getContentHash();
        Transformers\PygmentsCodeTransformer::reverseTransform($entity, $data);

        if ($data['languageId']) {
            $entity->setLanguage(
                $this->em->getReference(Entity\PygmentsLanguage::class, (int)$data['languageId'])
            );
        } else {
            $entity->setLanguage(null);
        }

        if ($oldHash !== $entity->getContentHash()) {
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
            $class = 'App\\API\\Transformers\\' . $matches[1] . 'Transformer';
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
            $class = 'App\\API\\Transformers\\' . $matches[1] . 'Transformer';
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
