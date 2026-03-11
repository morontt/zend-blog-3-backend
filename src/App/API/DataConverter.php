<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 12:33
 */

namespace App\API;

use App\API\Fractal\Collection;
use App\DTO\ArticleDTO;
use App\DTO\CategoryDTO;
use App\DTO\PygmentsCodeDTO;
use App\Entity;
use App\Model\Image;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Service\TextProcessor;
use App\Utils\Inflector;
use App\Utils\Pygment;
use App\Utils\RuTransform;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use RuntimeException;

/**
 * Class DataConverter
 *
 * @method array<string, mixed> getCategory(Entity\Category $entity, $includes = null)
 * @method array<int, array<string, mixed>> getCategoryArray($collection, $includes = null)
 * @method array<string, mixed> getComment(Entity\Comment $entity, $includes = null)
 * @method array<int, array<string, mixed>> getCommentArray($collection, $includes = null)
 * @method array<string, mixed> getCommentator(Entity\CommentatorInterface $entity, $includes = null)
 * @method array<int, array<string, mixed>> getCommentatorArray($collection, $includes = null)
 * @method array<string, mixed> getMediaFile(Image $entity, $includes = null)
 * @method array<int, array<string, mixed>> getMediaFileArray($collection, $includes = null)
 * @method array<string, mixed> getPost(Entity\Post $entity, $includes = null)
 * @method array<int, array<string, mixed>> getPostArray($collection, $includes = null)
 * @method array<string, mixed> getTag(Entity\Tag $entity, $includes = null)
 * @method array<int, array<string, mixed>> getTagArray($collection, $includes = null)
 * @method array<string, mixed> getPygmentsLanguage(Entity\PygmentsLanguage $entity, $includes = null)
 * @method array<int, array<string, mixed>> getPygmentsLanguageArray($collection, $includes = null)
 * @method array<string, mixed> getPygmentsCode(Entity\PygmentsCode $entity, $includes = null)
 * @method array<int, array<string, mixed>> getPygmentsCodeArray($collection, $includes = null)
 * @method array<string, mixed> getUserAgent(Entity\TrackingAgent $entity, $includes = null)
 * @method array<int, array<string, mixed>> getUserAgentArray($collection, $includes = null)
 * @method array<string, mixed> getTracking(Entity\Tracking $entity, $includes = null)
 * @method array<int, array<string, mixed>> getTrackingArray($collection, $includes = null)
 * @method array<string, mixed> getTelegramUser(Entity\TelegramUser $entity, $includes = null)
 * @method array<int, array<string, mixed>> getTelegramUserArray($collection, $includes = null)
 * @method array<string, mixed> getTelegramUpdate(Entity\TelegramUpdate $entity, $includes = null)
 * @method array<int, array<string, mixed>> getTelegramUpdateArray($collection, $includes = null)
 * @method array<string, mixed> getUser(Entity\User $entity, $includes = null)
 * @method array<int, array<string, mixed>> getUserArray($collection, $includes = null)
 */
class DataConverter
{
    private Manager $fractal;

    public function __construct(
        private EntityManagerInterface $em,
        private TextProcessor $textProcessor,
        private CommentRepository $commentsRepository,
        private CategoryRepository $categoryRepository,
    ) {
        $this->fractal = new Manager();
        $this->fractal->setSerializer(new Serializer());
    }

    /**
     * @return array<string, mixed>
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
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function saveCommentator(Entity\Commentator $entity, array $data): array
    {
        Transformers\CommentatorTransformer::reverseTransform($entity, $data);

        $this->save($entity);

        return $this->getCommentator($entity);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function saveTrackingAgent(Entity\TrackingAgent $entity, array $data): array
    {
        Transformers\UserAgentTransformer::reverseTransform($entity, $data);

        $this->save($entity);

        return $this->getUserAgent($entity);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function saveComment(Entity\Comment $entity, array $data): array
    {
        Transformers\CommentTransformer::reverseTransform($entity, $data);
        $this->commentsRepository->save($entity);

        return $this->getComment($entity);
    }

    /**
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return array<string, mixed>
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

        /** @var \App\Repository\TagRepository $tagRepository */
        $tagRepository = $this->em->getRepository(Entity\Tag::class);
        $tagsArray = array_map('trim', explode(',', $data['tagsString']));
        foreach ($tagsArray as $tagName) {
            if ($tagName) {
                $tag = $tagRepository->getTagForPost($tagName);
                if ($tag) {
                    // @phpstan-ignore if.alwaysFalse
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

        if (is_null($entity->getId())) {
            $postRepository = $this->em->getRepository(Entity\Post::class);
            $slug = $entity->getUrl();
            $oldPost = $postRepository->findOneBy(['url' => $slug]);
            if ($oldPost) {
                $inc = 2;
                do {
                    $newSlug = $slug . '-' . $inc;
                    $oldPost = $postRepository->findOneBy(['url' => $newSlug]);
                    $inc++;
                } while ($oldPost);
                $entity->setUrl($newSlug);
            }
        }

        $this->save($entity);

        if ($media = $entity->getDefaultImage()) {
            $media->setLastUpdate(new DateTime());
            $this->em->flush();
        }

        return $this->getPost($entity);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function saveMediaFile(Entity\MediaFile $entity, array $data): array
    {
        $oldPostId = null;
        if ($oldPost = $entity->getPost()) {
            $oldPostId = $oldPost->getId();
        }

        Transformers\MediaFileTransformer::reverseTransform($entity, $data);

        $newPostId = null;
        if ($data['postId']) {
            $post = $this->em->getRepository(Entity\Post::class)->find((int)$data['postId']);
            if ($post) {
                $entity->setPost($post);
                if ($this->em->getRepository(Entity\MediaFile::class)->getCountByPostId((int)$data['postId']) === 0) {
                    $entity->setDefaultImage(true);
                }
                $newPostId = $post->getId();
            }
        } else {
            $entity->setPost(null);
        }

        $this->save($entity);

        if ($oldPostId) {
            $post = $this->em->getRepository(Entity\Post::class)->find($oldPostId);
            $this->textProcessor->processing($post);
            $this->em->flush();
        }

        if ($newPostId && $newPostId !== $oldPostId) {
            $post = $this->em->getRepository(Entity\Post::class)->find($newPostId);
            $this->textProcessor->processing($post);
            $this->em->flush();
        }

        return $this->getMediaFile(new Image($entity));
    }

    /**
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return array<string, mixed>
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
     * @param string $method
     * @param array<int, mixed> $arguments
     *
     * @return array<string, mixed>|null
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

            $scope = $this->fractal->createData($resource);
        } elseif (preg_match('/^get([A-Z]\w+)$/', $method, $matches)) {
            $class = 'App\\API\\Transformers\\' . $matches[1] . 'Transformer';
            $resource = new Item($arguments[0], new $class(), lcfirst($matches[1]));

            if (!empty($arguments[1])) {
                $this->fractal->parseIncludes($arguments[1]);
            }

            $scope = $this->fractal->createData($resource);
        } else {
            throw new RuntimeException(sprintf('Undefined method: %s', $method));
        }

        return $scope->toArray();
    }

    protected function save(object $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
