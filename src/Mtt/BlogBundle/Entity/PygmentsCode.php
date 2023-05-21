<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mtt\BlogBundle\Entity\Traits\ModifyEntityTrait;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\PygmentsCodeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PygmentsCode
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
     * @var PygmentsLanguage
     *
     * @ORM\ManyToOne(targetEntity="PygmentsLanguage")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535)
     */
    private $sourceCode;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $sourceHtml;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PygmentsLanguage|null
     */
    public function getLanguage(): ?PygmentsLanguage
    {
        return $this->language;
    }

    /**
     * @param PygmentsLanguage|null $language
     *
     * @return PygmentsCode
     */
    public function setLanguage(PygmentsLanguage $language = null): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSourceCode(): ?string
    {
        return $this->sourceCode;
    }

    /**
     * @param mixed $sourceCode
     *
     * @return PygmentsCode
     */
    public function setSourceCode($sourceCode): self
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSourceHtml(): ?string
    {
        return $this->sourceHtml;
    }

    /**
     * @param string $sourceHtml
     *
     * @return PygmentsCode
     */
    public function setSourceHtml(string $sourceHtml): self
    {
        $this->sourceHtml = $sourceHtml;

        return $this;
    }
}
