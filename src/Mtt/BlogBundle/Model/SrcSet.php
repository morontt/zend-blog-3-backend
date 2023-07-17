<?php

namespace Mtt\BlogBundle\Model;

use Mtt\BlogBundle\Service\ImageManager;

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
     * @param array $items
     *
     * @return SrcSet
     */
    public function setOrigin(array $items): SrcSet
    {
        $this->origin = new SrcSetItem($items);

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
     * @param array $items
     *
     * @return SrcSet
     */
    public function setWebp(array $items): SrcSet
    {
        $this->webp = new SrcSetItem($items);
        if ($this->origin && !$this->isSizeSmaller($items)) {
            $this->webp = null;
        }

        return $this;
    }

    public function isSizeSmaller(array $items)
    {
        if (!$this->origin) {
            return true;
        }

        $firstItemSize = 0;
        foreach ($items as $item) {
            $firstItemSize = filesize(ImageManager::getUploadsDir() . '/' . $item['path']);
            break;
        }

        $originItemSize = 0;
        foreach ($this->origin->getItems() as $item) {
            $originItemSize = filesize(ImageManager::getUploadsDir() . '/' . $item['path']);
            break;
        }

        return $firstItemSize < $originItemSize;
    }
}
