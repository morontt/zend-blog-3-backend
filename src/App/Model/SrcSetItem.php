<?php

declare(strict_types=1);

namespace App\Model;

class SrcSetItem
{
    /** @var list<array<string, mixed>> */
    private array $items;

    /**
     * @param list<array<string, mixed>> $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return list<array<string, mixed>>
     */
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

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'items' => $this->items,
            'type' => $this->getMIMEType(),
        ];
    }
}
