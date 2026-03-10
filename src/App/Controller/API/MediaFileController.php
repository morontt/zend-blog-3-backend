<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 04.04.16
 * Time: 22:34
 */

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Entity\MediaFile;
use App\Exception\ObjectNotFoundException;
use App\Form\AvatarFormType;
use App\Form\ImageFormType;
use App\Model\Image;
use App\Repository\MediaFileRepository;
use App\Service\ImageManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/mediaFiles')]
class MediaFileController extends BaseController
{
    /**
     * @param Request $request
     * @param MediaFileRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, MediaFileRepository $repository): JsonResponse
    {
        $params = $request->query->all();

        if (!empty($params['post_id'])) {
            $result = $this->getDataConverter()
                ->getMediaFileArray(
                    array_map(
                        function (MediaFile $e) {
                            return new Image($e);
                        },
                        $repository->getFilesByPost((int)$params['post_id'])
                    )
                );
        } else {
            $pagination = $this->paginate(
                $repository->getListQuery(),
                $request->query->get('page', 1)
            );

            $result = $this->getDataConverter()
                ->getMediaFileArray(
                    array_map(
                        function (MediaFile $e) {
                            return new Image($e);
                        },
                        $pagination->getItems()
                    ),
                    'post'
                );

            $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());
        }

        return new JsonResponse($result);
    }

    /**
     * @param MediaFile $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(MediaFile $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getMediaFile(new Image($entity));

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     * @param MediaFile $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateAction(Request $request, MediaFile $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->saveMediaFile($entity, $request->request->get('mediaFile'));

        return new JsonResponse($result);
    }

    /**
     * @param MediaFile $entity
     * @param ImageManager $manager
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteAction(MediaFile $entity, ImageManager $manager): JsonResponse
    {
        $manager->remove($entity);

        return new JsonResponse(true);
    }

    /**
     * @param Request $request
     * @param ImageManager $manager
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return JsonResponse
     */
    #[Route(path: '/upload', name: 'upload_image', options: ['expose' => true], methods: ['POST'])]
    public function uploadAction(Request $request, ImageManager $manager): JsonResponse
    {
        $form = $this->createForm(ImageFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->uploadImage(
                $form->get('description')->getData(),
                $form->get('post_id')->getData(),
                $form->get('upload')->getData()
            );
        } else {
            $messages = [];
            foreach ($form->get('upload')->getErrors() as $error) {
                $messages[] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $messages], 422);
        }

        return new JsonResponse(true, 201);
    }

    /**
     * @param Request $request
     * @param ImageManager $manager
     *
     * @throws \Doctrine\ORM\Exception\NotSupported
     * @throws \Doctrine\ORM\Exception\ORMException
     *
     * @return JsonResponse
     */
    #[Route(path: '/upload-avatar', name: 'upload_avatar', options: ['expose' => true], methods: ['POST'])]
    public function uploadAvatarAction(Request $request, ImageManager $manager): JsonResponse
    {
        $form = $this->createForm(AvatarFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $manager->uploadAvatar(
                    $form->get('commentator_id')->getData(),
                    $form->get('upload')->getData()
                );
            } catch (ObjectNotFoundException $e) {
                return new JsonResponse(['errors' => ['Commentator not found']], 422);
            }
        } else {
            $messages = [];
            foreach ($form->get('upload')->getErrors() as $error) {
                $messages[] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $messages], 422);
        }

        return new JsonResponse(true, 201);
    }
}
