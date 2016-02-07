<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 28.03.15
 * Time: 19:46
 */

namespace Mtt\BlogBundle\API;

use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\JsonApiSerializer;

class Serializer extends JsonApiSerializer
{
    /**
     * Serialize an item resource
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return array($resourceKey ?: 'data' => $data);
    }

    /**
     * Serialize the included data.
     *
     * @param ResourceInterface $resource
     * @param array $data
     *
     * @return array
     */
    public function includedData(ResourceInterface $resource, array $data)
    {
        $serializedData = [];

        foreach ($data as $value) {
            foreach ($value as $includeKey => $includeValue) {
                $serializedData = array_merge_recursive($serializedData, $includeValue);
            }
        }

        return empty($serializedData) ? [] : $serializedData;
    }
}
