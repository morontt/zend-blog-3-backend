<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 28.03.15
 * Time: 21:19
 */

namespace Mtt\BlogBundle\Controller\API;

use Doctrine\ORM\ORMException;
use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\Entity\Repository\TagRepository;
use Mtt\BlogBundle\Entity\Tag;
use Mtt\BlogBundle\Form\TagFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/tags")
 *
 * Class TagController
 */
class TagController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param TagRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, TagRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(true),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getTagArray($pagination);

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param Tag $entity
     *
     * @return JsonResponse
     */
    public function findAction(Tag $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getTag($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $form = $this->createObjectForm('tag', TagFormType::class);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()->saveTag(new Tag(), $formData['tag']);

        return new JsonResponse($result, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param Tag $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, Tag $entity): JsonResponse
    {
        $form = $this->createObjectForm('tag', TagFormType::class, true);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->getDataConverter()->saveTag($entity, $formData['tag']);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
     *
     * @param Tag $entity
     *
     * @throws ORMException
     *
     * @return JsonResponse
     */
    public function deleteAction(Tag $entity): JsonResponse
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }

    /**
     * @Route("/autocomplete", name="tags_autocomplete", options={"expose"=true}, methods={"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function tagAutocompleteAction(Request $request): JsonResponse
    {
        $tags = $this->getEm()
            ->getRepository('MttBlogBundle:Tag')
            ->getForAutocomplete($request->query->get('term'));

        $result = array_map(
            function (Tag $tag) {
                return ['value' => $tag->getName()];
            },
            $tags
        );

        return new JsonResponse($result);
    }
}
