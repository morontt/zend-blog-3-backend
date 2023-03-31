<?php

namespace Mtt\BlogBundle\Entity\Embedded;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class NestedSet
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     */
    private $leftKey;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned": true})
     */
    private $rightKey;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned": true, "default": 1})
     */
    private $depth = 1;

    /**
     * @return int
     */
    public function getLeftKey(): int
    {
        return $this->leftKey;
    }

    /**
     * @param int $leftKey
     *
     * @return NestedSet
     */
    public function setLeftKey(int $leftKey): self
    {
        $this->leftKey = $leftKey;

        return $this;
    }

    /**
     * @return int
     */
    public function getRightKey(): int
    {
        return $this->rightKey;
    }

    /**
     * @param int $rightKey
     *
     * @return NestedSet
     */
    public function setRightKey(int $rightKey): self
    {
        $this->rightKey = $rightKey;

        return $this;
    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     *
     * @return NestedSet
     */
    public function setDepth(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }
}
