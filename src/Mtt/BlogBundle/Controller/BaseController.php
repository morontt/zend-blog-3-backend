<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 16.11.14
 * Time: 17:43
 */

namespace Mtt\BlogBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Mtt\BlogBundle\API\DataConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * @var DataConverter
     */
    protected $apiDataConverter;

    /**
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param DataConverter $apiDataConverter
     */
    public function __construct(
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        DataConverter $apiDataConverter
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->apiDataConverter = $apiDataConverter;
    }

    /**
     * @return EntityManager
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @return DataConverter
     */
    public function getDataConverter(): DataConverter
    {
        return $this->apiDataConverter;
    }

    /**
     * @return Paginator
     */
    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    /**
     * @param $query
     * @param $page
     * @param int $limit
     *
     * @return SlidingPagination
     */
    public function paginate($query, $page, $limit = 15): PaginationInterface
    {
        return $this->getPaginator()
            ->paginate($query, (int)$page, $limit);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getPaginationMetadata(array $data): array
    {
        return [
            'last' => $data['last'],
            'current' => $data['current'],
            'previous' => $data['previous'] ?? false,
            'next' => $data['next'] ?? false,
        ];
    }
}
