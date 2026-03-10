<?php

/**
 * User: morontt
 * Date: 08.05.2025
 * Time: 10:04
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'lj_comments')]
#[ORM\Entity]
class LjComment
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var Comment
     */
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[ORM\OneToOne(targetEntity: Comment::class)]
    private $comment;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', unique: true)]
    private $ljId;

    /**
     * @var DateTime
     */
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $timeCreated;

    public function __construct()
    {
        $this->timeCreated = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLjId(): int
    {
        return $this->ljId;
    }

    public function setLjId(int $ljId): self
    {
        $this->ljId = $ljId;

        return $this;
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }

    public function setComment(Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }
}
