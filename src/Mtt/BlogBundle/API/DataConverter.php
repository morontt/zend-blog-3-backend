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

class DataConverter
{
    public function getCategoryArray(array $categories)
    {
        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer());

        $resource = new Collection($categories, new CategoryTransformer(), 'categories');

        return $fractal->createData($resource)->toArray();
    }
}
