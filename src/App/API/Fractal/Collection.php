<?php

/**
 * User: morontt
 * Date: 02.10.2024
 * Time: 09:53
 */

namespace App\API\Fractal;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use League\Fractal\Resource\Collection as FractalCollection;

class Collection extends FractalCollection
{
    public function __construct($data = null, $transformer = null, ?string $resourceKey = null)
    {
        parent::__construct($data, $transformer, $resourceKey);

        if ($data instanceof SlidingPagination) {
            $this->setPaginator(new KnpPaginatorAdapter($data));
        }
    }
}
