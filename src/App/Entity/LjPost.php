<?php
/**
 * User: morontt
 * Date: 07.05.2025
 * Time: 00:55
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lj_posts")
 *
 * @ORM\Entity()
 */
class LjPost
{
    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Post
     *
     * @ORM\OneToOne(targetEntity="Post")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    protected $post;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     */
    private $ljItemId;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getLjItemId(): int
    {
        return $this->ljItemId;
    }

    public function setLjItemId(int $ljItemId): self
    {
        $this->ljItemId = $ljItemId;

        return $this;
    }
}
