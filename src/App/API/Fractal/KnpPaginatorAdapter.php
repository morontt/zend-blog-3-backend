<?php

/**
 * User: morontt
 * Date: 02.10.2024
 * Time: 09:58
 */

namespace App\API\Fractal;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use League\Fractal\Pagination\PaginatorInterface;

class KnpPaginatorAdapter implements PaginatorInterface
{
    private array $paginationsData;

    public function __construct(SlidingPagination $paginator)
    {
        $this->paginationsData = $paginator->getPaginationData();
    }

    public function getCurrentPage(): int
    {
        return $this->paginationsData['current'];
    }

    public function getLastPage(): int
    {
        return $this->paginationsData['pageCount'];
    }

    public function getTotal(): int
    {
        return $this->paginationsData['totalCount'];
    }

    public function getCount(): int
    {
        return $this->paginationsData['currentItemCount'] ?? 0;
    }

    public function getPerPage(): int
    {
        return $this->paginationsData['numItemsPerPage'];
    }

    public function getUrl(int $page): string
    {
        return '/fake/' . $page;
    }
}
