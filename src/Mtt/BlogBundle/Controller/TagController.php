<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 28.03.15
 * Time: 21:19
 */

namespace Mtt\BlogBundle\Controller;

use Mtt\BlogBundle\Entity\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/tags")
 *
 * Class TagController
 * @package Mtt\BlogBundle\Controller
 */
class TagController extends BaseController
{
    /**
     * @Route("")
     * @Method("GET")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function findAllAction(Request $request)
    {
        $pagination = $this->paginate(
            $this->getTagRepository()->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getTagsArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param Tag $entity
     * @return JsonResponse
     */
    public function findAction(Tag $entity)
    {
        $result = $this->getDataConverter()
            ->getTag($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("PUT")
     *
     * @param Request $request
     * @param Tag $entity
     * @return JsonResponse
     */
    public function updateAction(Request $request, Tag $entity)
    {
        $result = $this->getDataConverter()
            ->saveTag($entity, $request->request->get('tag'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("DELETE")
     *
     * @param Tag $entity
     * @return JsonResponse
     */
    public function deleteAction(Tag $entity)
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }

    /**
     * @Route("")
     * @Method("POST")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createTagAction(Request $request)
    {
        $result = $this->getDataConverter()
            ->saveTag(new Tag(), $request->request->get('tag'));

        return new JsonResponse($result);
    }
}
