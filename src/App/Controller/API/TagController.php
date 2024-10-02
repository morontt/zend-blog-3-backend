<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 28.03.15
 * Time: 21:19
 */

namespace App\Controller\API;

use App\API\Transformers\TagTransformer;
use App\Controller\BaseController;
use App\Entity\Tag;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @param ValidatorInterface $validator
     * @param Request $request
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    public function createAction(ValidatorInterface $validator, Request $request): JsonResponse
    {
        $form = $this->createObjectForm('tag', TagFormType::class);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entity = new Tag();
        TagTransformer::reverseTransform($entity, $formData['tag']);

        $errors = $this->validate($validator, $entity);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->em->persist($entity);
        $this->em->flush();

        return new JsonResponse($this->getDataConverter()->getTag($entity), Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param ValidatorInterface $validator
     * @param Request $request
     * @param Tag $entity
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return JsonResponse
     */
    public function updateAction(ValidatorInterface $validator, Request $request, Tag $entity): JsonResponse
    {
        $form = $this->createObjectForm('tag', TagFormType::class, true);
        $form->handleRequest($request);

        [$formData, $errors] = $this->handleForm($form);
        if ($errors) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        TagTransformer::reverseTransform($entity, $formData['tag']);

        $errors = $this->validate($validator, $entity);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->em->persist($entity);
        $this->em->flush();

        return new JsonResponse($this->getDataConverter()->getTag($entity));
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
     * @throws \Doctrine\ORM\Exception\NotSupported
     *
     * @return JsonResponse
     */
    public function tagAutocompleteAction(Request $request): JsonResponse
    {
        $tags = $this->getEm()
            ->getRepository(Tag::class)
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
