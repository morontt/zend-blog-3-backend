<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 09.05.16
 * Time: 16:46
 */

namespace Mtt\BlogBundle\Cron\Daily;

use DisqusAPI;
use Doctrine\ORM\EntityManager;
use Mtt\BlogBundle\Cron\CronServiceInterface;
use Mtt\BlogBundle\Entity\Comment;
use Mtt\BlogBundle\Entity\Commentator;
use Mtt\BlogBundle\Entity\Post;
use Zend\Filter\StripTags;

class DisqusGrabber implements CronServiceInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var DisqusAPI
     */
    protected $disqus;

    /**
     * @var Commentator[]
     */
    protected $commentators = [];

    /**
     * @var Post[]
     */
    protected $threads = [];

    /**
     * @var string
     */
    protected $email;

    /**
     * @var int
     */
    protected $countImported = 0;

    /**
     * @param EntityManager $em
     * @param array $options
     * @param string $email
     */
    public function __construct(EntityManager $em, array $options, $email)
    {
        $this->em = $em;
        $this->options = $options;
        $this->email = $email;
    }

    public function run()
    {
        $lastComment = $this->em
            ->getRepository('MttBlogBundle:Comment')
            ->getLastDisqusComment();

        $this->disqus = new DisqusAPI($this->options['secret_key']);

        $requestOptions = [
            'forum' => $this->options['shortname'],
            'limit' => 100,
            'order' => 'asc',
        ];

        if ($lastComment) {
            $requestOptions['since'] = (int)$lastComment->getTimeCreated()->format('U');
        }

        $disqusPosts = $this->disqus->forums->listPosts($requestOptions);
        $comments = [];
        $threads = [];
        foreach ($disqusPosts as $item) {
            $comment = [
                'id' => (int)$item->id,
                'parent' => $item->parent,
                'thread' => (int)$item->thread,
                'message' => $item->raw_message,
                'created' => $item->createdAt,
                'author' => [
                    'name' => $item->author->name,
                    'website' => $item->author->url,
                    'id' => (int)$item->author->id,
                ],
            ];
            $comments[] = $comment;
            $threads[] = $comment['thread'];
        }
        $threads = array_unique($threads);
        $this->fillTheards($threads);

        $filter = new StripTags([
            'allowTags' => ['a', 's', 'b', 'i', 'em', 'strong', 'img', 'p'],
            'allowAttribs' => ['src', 'href', 'class', 'id'],
        ]);

        foreach ($comments as $disqusComment) {
            $existComment = $this->em->getRepository('MttBlogBundle:Comment')->findOneByDisqusId($disqusComment['id']);
            if (!$existComment) {
                $createdAt = date_create_from_format(
                    'Y-m-d\TH:i:s',
                    $disqusComment['created'],
                    new \DateTimeZone('UTC')
                );
                $createdAt->setTimezone(new \DateTimeZone(date_default_timezone_get()));

                $comment = new Comment();
                $comment
                    ->setText($filter->filter($disqusComment['message']))
                    ->setDisqusId($disqusComment['id'])
                    ->setTimeCreated($createdAt)
                    ->setPost(
                        isset($this->threads[$disqusComment['thread']]) ? $this->threads[$disqusComment['thread']] : null
                    )
                ;

                if ($disqusComment['parent']) {
                    $comment->setParent(
                        $this->em->getRepository('MttBlogBundle:Comment')->findOneByDisqusId($disqusComment['parent'])
                    );
                }

                if ($disqusComment['author']['id'] == $this->options['owner_id']) {
                    $comment->setUser(
                        $this->em->getRepository('MttUserBundle:User')->findOneBy(['mail' => $this->email])
                    );
                } else {
                    $comment->setCommentator($this->getCommentator($disqusComment['author']));
                }

                $this->em->persist($comment);
                $this->em->flush();

                $this->countImported += 1;
            }
        }

        foreach ($this->threads as $post) {
            $this->em->getConnection()
                ->query("CALL update_comments_count({$post->getId()})")
                ->fetch();
        }
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        $message = 'Nothing';
        if ($this->countImported == 1) {
            $message = '1 new comment';
        } elseif ($this->countImported > 1) {
            $message = $this->countImported . ' new comments';
        }

        return $message;
    }

    /**
     * @param array $data
     *
     * @return Commentator
     */
    protected function getCommentator(array $data)
    {
        if (empty($this->commentators[$data['id']])) {
            $commentator = $this->em->getRepository('MttBlogBundle:Commentator')->findOneByDisqusId($data['id']);
            if (!$commentator) {
                $commentator = new Commentator();
                $commentator
                    ->setName($data['name'])
                    ->setWebsite($data['website'])
                    ->setDisqusId($data['id'])
                ;

                $this->em->persist($commentator);
                $this->em->flush();
            }

            $this->commentators[$data['id']] = $commentator;
        } else {
            $commentator = $this->commentators[$data['id']];
        }

        return $commentator;
    }

    /**
     * @param array $threads
     */
    protected function fillTheards(array $threads)
    {
        $posts = $this->em->getRepository('MttBlogBundle:Post')->getPostsByDisqusThreads($threads);

        foreach ($posts as $post) {
            $this->threads[(int)$post->getDisqusThread()] = $post;
        }

        $unknownThreads = [];
        foreach ($threads as $thread) {
            if (empty($this->threads[$thread])) {
                $unknownThreads[] = $thread;
            }
        }

        $disqusThreads = $this->disqus->forums->listThreads([
            'forum' => $this->options['shortname'],
            'thread' => $unknownThreads,
        ]);

        $this->saveDisqusThreads($disqusThreads);
    }

    /**
     * @param array $disqusThreads
     */
    protected function saveDisqusThreads(array $disqusThreads)
    {
        foreach ($disqusThreads as $item) {
            $url = str_replace('/article/', '', $item->identifiers[0]);
            $post = $this->em->getRepository('MttBlogBundle:Post')->findOneBy(['url' => $url]);
            if ($post) {
                $post->setDisqusThread($item->id);
                $this->em->flush();
                $this->threads[$item->id] = $post;
            }
        }
    }
}
