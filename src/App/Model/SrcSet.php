<?php

declare(strict_types=1);

namespace App\Model;

use App\Service\ImageManager;

class SrcSet
{
    private ?SrcSetItem $origin = null;
    private ?SrcSetItem $webp = null;
    private ?SrcSetItem $avif = null;

    /**
     * @return SrcSetItem|null
     */
    public function getOrigin(): ?SrcSetItem
    {
        return $this->origin;
    }

    /**
     * @param list<array<string, mixed>> $items
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
     * @param list<array<string, mixed>> $items
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

    /**
     * @return SrcSetItem|null
     */
    public function getAvif(): ?SrcSetItem
    {
        return $this->avif;
    }

    /**
     * @param list<array<string, mixed>> $items
     *
     * @return SrcSet
     */
    public function setAvif(array $items): SrcSet
    {
        $this->avif = new SrcSetItem($items);
        if ($this->origin && !$this->isSizeSmaller($items)) {
            $this->avif = null;
        }

        return $this;
    }

    /**
     * @param list<array<string, mixed>> $items
     */
    public function isSizeSmaller(array $items): bool
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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function toArray(): array
    {
        $data = [];
        if ($this->origin && count($this->origin->getItems())) {
            $data['origin'] = $this->origin->toArray();
        }
        if ($this->avif && count($this->avif->getItems())) {
            $data['avif'] = $this->avif->toArray();
        }
        if ($this->webp && count($this->webp->getItems())) {
            $data['webp'] = $this->webp->toArray();
        }

        return $data;
    }
}
