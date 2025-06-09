<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 28.03.15
 * Time: 19:46
 */

namespace App\API;

use App\Utils\Inflector;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\ArraySerializer;

class Serializer extends ArraySerializer
{
    private array $pluralizeCache = [];

    /**
     * Serialize an item.
     *
     * @param string|null $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function item(?string $resourceKey, array $data): array
    {
        return [$resourceKey ?: 'data' => $data];
    }

    /**
     * Serialize a collection.
     *
     * @param string|null $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function collection(?string $resourceKey, array $data): array
    {
        $key = $resourceKey ?: 'data';

        $resources = [];
        foreach ($data as $resource) {
            $resources[] = $this->item($key, $resource)[$key];
        }

        return [$key => $resources];
    }

    /**
     * Serialize the included data.
     *
     * @param ResourceInterface $resource
     * @param array $data
     *
     * @return array
     */
    public function includedData(ResourceInterface $resource, array $data): array
    {
        $serializedData = [];
        foreach ($data as $value) {
            foreach ($value as $includeValue) {
                $fk = array_key_first($includeValue);
                if ($this->isCollection($includeValue)) {
                    if (empty($serializedData[$fk])) {
                        $serializedData[$fk] = [];
                    }
                    $serializedData[$fk] = array_merge($serializedData[$fk], $includeValue[$fk]);
                } else {
                    $key = $this->pluralize($fk);
                    if (empty($serializedData[$key])) {
                        $serializedData[$key] = [];
                    }
                    $serializedData[$key][] = $includeValue[$fk];
                }
            }
        }

        return empty($serializedData) ? [] : $serializedData;
    }

    /**
     * Serialize the paginator.
     *
     * @param PaginatorInterface $paginator
     *
     * @return array[]
     */
    public function paginator(PaginatorInterface $paginator): array
    {
        $current = $paginator->getCurrentPage();
        $data = [];
        if ($current - 1 > 0) {
            $data['previous'] = $current - 1;
        }

        if ($current + 1 <= $paginator->getLastPage()) {
            $data['next'] = $current + 1;
        }

        return [
            'pagination' => [
                'last' => $paginator->getLastPage(),
                'current' => $current,
                'previous' => $data['previous'] ?? false,
                'next' => $data['next'] ?? false,
            ],
        ];
    }

    /**
     * Serialize the meta.
     *
     * @param array $meta
     *
     * @return array
     */
    public function meta(array $meta): array
    {
        return !empty($meta['pagination']) ? ['meta' => $meta['pagination']] : [];
    }

    public function sideloadIncludes(): bool
    {
        return true;
    }

    private function isCollection(array $data): bool
    {
        $fk = array_key_first($data);

        return !isset($data[$fk]['id']);
    }

    private function pluralize(string $word): string
    {
        if (!empty($this->pluralizeCache[$word])) {
            return $this->pluralizeCache[$word];
        }

        $res = Inflector::pluralize($word);
        $this->pluralizeCache[$word] = $res;

        return $res;
    }
}
