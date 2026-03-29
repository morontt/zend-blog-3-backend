<?php

/**
 * User: morontt
 * Date: 29.03.2026
 * Time: 18:09
 */

namespace App\Entity;

use App\Repository\ViewTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'v_tags')]
#[ORM\Entity(readOnly: true, repositoryClass: ViewTagRepository::class)]
class ViewTag implements TagInterface
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    private $url;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', name: 'cnt')]
    private $postsCount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPostsCount(): int
    {
        return $this->postsCount;
    }
}
