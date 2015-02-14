<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 15.02.15
 * Time: 0:06
 */

namespace Mtt\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/commentators")
 *
 * Class CommentatorController
 * @package Mtt\BlogBundle\Controller
 */
class CommentatorController extends BaseController
{
    /**
     * @Route("")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function findAllAction()
    {
        $result = $this->getDataConverter()
            ->getCommentatorsArray($this->getCommentatorRepository()->findAll());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param $id
     * @return JsonResponse
     */
    public function findAction($id)
    {
        /**
         * @var \Mtt\BlogBundle\Entity\Commentator $entity
         */
        $entity = $this->getCommentatorRepository()->find((int)$id);

        $result = $this->getDataConverter()
            ->getCommentator($entity);

        return new JsonResponse($result);
    }
}
