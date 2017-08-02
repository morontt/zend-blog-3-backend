<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 16.11.14
 * Time: 17:43
 */

namespace Mtt\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return \Mtt\BlogBundle\Entity\Repository\CategoryRepository
     */
    public function getCategoryRepository()
    {
        return $this->getEm()->getRepository('MttBlogBundle:Category');
    }

    /**
     * @return \Mtt\BlogBundle\Entity\Repository\TagRepository
     */
    public function getTagRepository()
    {
        return $this->getEm()->getRepository('MttBlogBundle:Tag');
    }

    /**
     * @return \Mtt\BlogBundle\Entity\Repository\CommentatorRepository
     */
    public function getCommentatorRepository()
    {
        return $this->getEm()->getRepository('MttBlogBundle:Commentator');
    }

    /**
     * @return \Mtt\BlogBundle\Entity\Repository\CommentRepository
     */
    public function getCommentRepository()
    {
        return $this->getEm()->getRepository('MttBlogBundle:Comment');
    }

    /**
     * @return \Mtt\BlogBundle\Entity\Repository\ViewCommentRepository
     */
    public function getViewCommentRepository()
    {
        return $this->getEm()->getRepository('MttBlogBundle:ViewComment');
    }

    /**
     * @return \Mtt\BlogBundle\Entity\Repository\PostRepository
     */
    public function getPostRepository()
    {
        return $this->getEm()->getRepository('MttBlogBundle:Post');
    }

    /**
     * @return \Mtt\BlogBundle\Entity\Repository\MediaFileRepository
     */
    public function getMediaFileRepository()
    {
        return $this->getEm()->getRepository('MttBlogBundle:MediaFile');
    }

    /**
     * @return \Mtt\BlogBundle\API\DataConverter
     */
    public function getDataConverter()
    {
        return $this->get('mtt_blog.api.data_converter');
    }

    /**
     * @return \Knp\Component\Pager\Paginator
     */
    public function getPaginator()
    {
        return $this->get('knp_paginator');
    }

    /**
     * @param $query
     * @param $page
     * @param int $limit
     *
     * @return \Knp\Component\Pager\Pagination\SlidingPagination
     */
    public function paginate($query, $page, $limit = 15)
    {
        return $this->getPaginator()
            ->paginate($query, (int)$page, $limit);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getPaginationMetadata(array $data)
    {
        $result = [
            'last' => $data['last'],
            'current' => $data['current'],
            'previous' => isset($data['previous']) ? $data['previous'] : false,
            'next' => isset($data['next']) ? $data['next'] : false,
        ];

        return $result;
    }
}
