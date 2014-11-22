<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 22.11.14
 * Time: 12:33
 */

namespace Mtt\BlogBundle\API;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;
use Mtt\BlogBundle\API\Transformers\CategoryTransformer;
use Mtt\BlogBundle\API\Transformers\TagTransformer;

class DataConverter
{
    /**
     * @param array $categories
     * @return array
     */
    public function getCategoryArray(array $categories)
    {
        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer());

        $resource = new Collection($categories, new CategoryTransformer(), 'categories');

        return $fractal->createData($resource)->toArray();
    }

    /**
     * @param array $tags
     * @return array
     */
    public function getTagsArray(array $tags)
    {
        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer());

        $resource = new Collection($tags, new TagTransformer(), 'tags');

        return $fractal->createData($resource)->toArray();
    }
}
