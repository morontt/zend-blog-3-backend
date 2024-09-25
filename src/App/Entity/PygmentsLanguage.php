<?php

namespace App\Entity;

use App\Entity\Traits\ModifyEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\PygmentsLanguageRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"name"})
 */
class PygmentsLanguage
{
    use ModifyEntityTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, unique=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $lexer;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return PygmentsLanguage
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLexer(): ?string
    {
        return $this->lexer;
    }

    /**
     * @param string|null $lexer
     *
     * @return PygmentsLanguage
     */
    public function setLexer(string $lexer = null): self
    {
        $this->lexer = $lexer;

        return $this;
    }
}
