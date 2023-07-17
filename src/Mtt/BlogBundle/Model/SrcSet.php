<?php

namespace Mtt\BlogBundle\Model;

class SrcSet
{
    private ?SrcSetItem $origin;
    private ?SrcSetItem $webp;

    /**
     * @return SrcSetItem|null
     */
    public function getOrigin(): ?SrcSetItem
    {
        return $this->origin;
    }

    /**
     * @param array $origin
     *
     * @return SrcSet
     */
    public function setOrigin(array $origin): SrcSet
    {
        $this->origin = new SrcSetItem($origin);

        return $this;
    }

    /**
     * @return SrcSetItem|null
     */
    public function getWebp(): ?SrcSetItem
    {
        return $this->webp;
    }

    /**
     * @param array $webp
     *
     * @return SrcSet
     */
    public function setWebp(array $webp): SrcSet
    {
        $this->webp = new SrcSetItem($webp);

        return $this;
    }
}
