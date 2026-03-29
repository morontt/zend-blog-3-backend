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
use App\Repository\ViewTagRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/api/tags')]
class TagController extends BaseController
{
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, ViewTagRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(true),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getTagArray($pagination);

        return new JsonResponse($result);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(Tag $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getTag($entity);

        return new JsonResponse($result);
    }

    #[Route(path: '', methods: ['POST'])]
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

        $this->getEm()->persist($entity);
        $this->getEm()->flush();

        return new JsonResponse($this->getDataConverter()->getTag($entity), Response::HTTP_CREATED);
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
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

        $this->getEm()->persist($entity);
        $this->getEm()->flush();

        return new JsonResponse($this->getDataConverter()->getTag($entity));
    }

    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteAction(Tag $entity): JsonResponse
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }

    #[Route(path: '/autocomplete', name: 'tags_autocomplete', options: ['expose' => true], methods: ['GET'])]
    public function tagAutocompleteAction(Request $request, TagRepository $tagRepo): JsonResponse
    {
        $tags = $tagRepo->getForAutocomplete((string)$request->query->get('term'));

        $result = array_map(
            function (Tag $tag) {
                return ['value' => $tag->getName()];
            },
            $tags
        );

        return new JsonResponse($result);
    }
}
