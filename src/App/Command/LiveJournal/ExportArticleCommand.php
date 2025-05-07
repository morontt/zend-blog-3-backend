<?php
/**
 * User: morontt
 * Date: 06.05.2025
 * Time: 22:43
 */

namespace App\Command\LiveJournal;

use App\API\DataConverter;
use App\DTO\ArticleDTO;
use App\DTO\CommentatorDTO;
use App\DTO\CommentDTO;
use App\DTO\CommentUserDTO;
use App\Entity\LjCommentMeta;
use App\Entity\LjPost;
use App\Entity\Post;
use App\Service\CommentManager;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use SimpleXMLElement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ExportArticleCommand extends Command
{
    private DataConverter $dataConverter;
    private EntityManagerInterface $em;
    private CommentManager $commentManager;
    private EntityRepository $commentMetaRepo;

    private array $postersMap = [];
    private array $commentsMap = [];

    public function __construct(
        DataConverter $dataConverter,
        EntityManagerInterface $em,
        CommentManager $commentManager
    ) {
        parent::__construct();

        $this->dataConverter = $dataConverter;
        $this->commentManager = $commentManager;
        $this->em = $em;

        $this->commentMetaRepo = $this->em->getRepository(LjCommentMeta::class);

        $anonymous = new CommentatorDTO();
        $anonymous->name = 'Anonymous';
        $this->postersMap[0] = $anonymous;
    }

    protected function configure(): void
    {
        $this
            ->setName('mtt:lj:export')
            ->setDescription('Export article and comments')
            ->addArgument('articleId', InputArgument::REQUIRED, 'article ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $articleId = (int)$input->getArgument('articleId');

        $obj = $this->em->getRepository(LjPost::class)->findOneBy(['ljItemId' => $articleId]);
        if ($obj) {
            $output->writeln("Was exported:\tID=<info>" . $obj->getPost()->getId() . '</info>');

            return 0;
        }

        $ljPost = $this->exportArticle($articleId, $output);
        if (is_null($ljPost)) {
            return 1;
        }

        $this->exportComments($ljPost, $output);

        return 0;
    }

    private function exportArticle(int $articleId, OutputInterface $output): ?LjPost
    {
        $articleDTO = null;
        foreach (glob(APP_VAR_DIR . '/livejournal/posts-xml/*.xml') as $filename) {
            $file = pathinfo($filename, PATHINFO_BASENAME);
            $dataStr = @file_get_contents($filename);
            if ($dataStr === false) {
                $output->writeln('<error>file ' . $file . ' cannot be read</error>');

                return null;
            }

            $data = @simplexml_load_string($dataStr);
            if ($data === false) {
                $output->writeln('<error>invalid data on file ' . $file . '</error>');

                return null;
            }

            foreach ($data->entry as $item) {
                if ((int)$item->itemid === $articleId) {
                    $articleDTO = new ArticleDTO();

                    $articleDTO->hidden = true;
                    $articleDTO->title = (string)$item->subject;
                    $articleDTO->categoryId = 91;
                    $articleDTO->text = (string)$item->event;
                    $articleDTO->tagsString = 'из жеже';

                    $output->writeln(sprintf("Parsed file:\t<comment>%s</comment>", $file));
                    $output->writeln(sprintf("Article found:\t<info>%s</info>", $articleDTO->title));

                    $created = new DateTime($item->logtime, new DateTimeZone('UTC'));
                    $created->setTimezone(new DateTimeZone('Europe/Kiev'));

                    $output->writeln(sprintf("Log time:\t<comment>%s</comment>", $created->format('Y-m-d H:i:s')));

                    $articleDTO->forceCreatedAt = $created->format('Y-m-d H:i:s') . '.' . (new DateTime())->format('v');

                    break 2;
                }
            }
        }

        if ($articleDTO === null) {
            $output->writeln('<error>Article not found</error>');

            return null;
        }

        $post = new Post();
        $this->dataConverter->savePost($post, $articleDTO);

        $output->writeln('');
        $output->writeln("Save article:\tID=<info>" . $post->getId() . '</info>');

        $ljPost = new LjPost();
        $ljPost
            ->setLjItemId($articleId)
            ->setPost($post)
        ;

        $this->em->persist($ljPost);
        $this->em->flush();

        return $ljPost;
    }

    private function exportComments(LjPost $ljPost, OutputInterface $output)
    {
        $output->writeln("\nExport comments:");

        $ljitem = (string)($ljPost->getLjItemId() >> 8);
        $postId = $ljPost->getPost()->getId();

        $output->writeln("LjItem:\t" . $ljitem);

        foreach (glob(APP_VAR_DIR . '/livejournal/comments-xml/comment_body-*.xml') as $filename) {
            $file = pathinfo($filename, PATHINFO_BASENAME);
            $output->writeln('parsing <comment>' . $file . '</comment>');
            $dataStr = @file_get_contents($filename);
            if ($dataStr === false) {
                $output->writeln('<error>file ' . $file . ' cannot be read</error>');

                return null;
            }

            $data = @simplexml_load_string($dataStr);
            if ($data === false) {
                $output->writeln('<error>invalid data on file ' . $file . '</error>');

                return null;
            }

            foreach ($data->comments[0] as $item) {
                if ((string)$item['jitemid'] === $ljitem) {
                    $this->saveComment($item, $postId, $output);
                }
            }
        }
    }

    private function saveComment(SimpleXMLElement $data, int $postId, OutputInterface $output)
    {
        $dto = new CommentDTO();

        $dto->topicId = $postId;
        $dto->userAgent = 'mtt:lj:export';

        $text = '';
        $subject = (string)$data->subject;
        if ($subject) {
            $text .= $subject . "\n\n";
        }
        $body = (string)$data->body;
        if ($body) {
            $text .= $body;
        }

        $dto->text = $text ?: 'empty body';

        $created = new DateTime((string)$data->date);
        $created->setTimezone(new DateTimeZone('Europe/Kiev'));
        $dto->forceCreatedAt = $created->format('Y-m-d H:i:s') . '.' . (new DateTime())->format('v');

        $posterId = (int)$data['posterid'];
        $this->setSender($dto, $posterId);

        if ((string)$data['state']) {
            $dto->deleted = true;
        }

        $parentId = (string)$data['parentid'];
        if ($parentId && isset($this->commentsMap[$parentId])) {
            $dto->parentId = $this->commentsMap[$parentId];
        }

        try {
            $comment = $this->commentManager->saveExternalComment($dto);
            $output->writeln('Comment saved, ID: <comment>' . $data['id'] . '</comment>');
        } catch (Throwable $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            exit;
        }

        if (!isset($this->postersMap[$posterId])) {
            $user = $comment->getUser();
            if ($user) {
                $userDto = new CommentUserDTO();
                $userDto->id = $user->getId();
                $this->postersMap[$posterId] = $userDto;
            }

            $commentator = $comment->getCommentator();
            if ($commentator) {
                $commentatorDto = new CommentatorDTO();
                $commentatorDto->id = $commentator->getId();
                $this->postersMap[$posterId] = $commentatorDto;

                $meta = $this->commentMetaRepo->findOneBy(['posterId' => $posterId]);
                if ($meta) {
                    $meta->setCommentator($commentator);
                    $this->em->flush();
                }
            }
        }

        $this->commentsMap[(string)$data['id']] = $comment->getId();
    }

    private function setSender(CommentDTO $commentDTO, int $posterId): void
    {
        if ($posterId === 0) {
            $commentDTO->commentator = $this->postersMap[0];

            return;
        }

        if (isset($this->postersMap[$posterId])) {
            $cached = $this->postersMap[$posterId];
            if ($cached instanceof CommentUserDTO) {
                $commentDTO->user = $cached;
            } elseif ($cached instanceof CommentatorDTO) {
                $commentDTO->commentator = $cached;
            }

            return;
        }

        /* @var LjCommentMeta|null $meta */
        $meta = $this->commentMetaRepo->findOneBy(['posterId' => $posterId]);
        if (!$meta) {
            $commentDTO->commentator = $this->postersMap[0];

            return;
        }

        $user = $meta->getUser();
        if ($user) {
            $userDto = new CommentUserDTO();
            $userDto->id = $user->getId();
            $commentDTO->user = $userDto;

            return;
        }

        $commentatorDto = new CommentatorDTO();
        $commentator = $meta->getCommentator();
        if ($commentator) {
            $commentatorDto->id = $commentator->getId();
        } else {
            $commentatorDto->name = $meta->getLjName();
        }

        $commentDTO->commentator = $commentatorDto;
    }
}
