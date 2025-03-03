<?php

namespace App\Model;

class SrcSetItem
{
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getMIMEType(): ?string
    {
        foreach ($this->items as $item) {
            switch (strtolower(pathinfo($item['path'], PATHINFO_EXTENSION))) {
                case 'jpeg':
                case 'jpg':
                    return 'image/jpeg';
                case 'png':
                    return 'image/png';
                case 'webp':
                    return 'image/webp';
                case 'avif':
                    return 'image/avif';
            }
        }

        return null;
    }

    public function toArray(): array
    {
        return [
            'items' => $this->items,
            'type' => $this->getMIMEType(),
        ];
    }
}
