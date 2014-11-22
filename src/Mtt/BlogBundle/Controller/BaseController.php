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
     * @return \Mtt\BlogBundle\API\DataConverter
     */
    public function getDataConverter()
    {
        return $this->get('mtt_blog.api.data_converter');
    }
}
