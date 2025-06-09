<?php

/**
 * User: morontt
 * Date: 07.05.2025
 * Time: 00:55
 */

namespace App\Entity;

use DateTime;
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
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $post;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", unique=true)
     */
    private $ljItemId;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $timeCreated;

    public function __construct()
    {
        $this->timeCreated = new DateTime();
    }

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
