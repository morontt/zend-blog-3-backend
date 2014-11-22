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
     * @var Manager
     */
    protected $fractal;


    public function __construct()
    {
        $this->fractal = new Manager();
        $this->fractal->setSerializer(new JsonApiSerializer());
    }

    /**
     * @param array $categories
     * @return array
     */
    public function getCategoryArray(array $categories)
    {
        $resource = new Collection($categories, new CategoryTransformer(), 'categories');

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * @param array $tags
     * @return array
     */
    public function getTagsArray(array $tags)
    {
        $resource = new Collection($tags, new TagTransformer(), 'tags');

        return $this->fractal->createData($resource)->toArray();
    }
}
