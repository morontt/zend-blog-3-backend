<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 04.04.16
 * Time: 22:34
 */

namespace Mtt\BlogBundle\Controller\API;

use Doctrine\ORM\ORMException;
use Mtt\BlogBundle\Controller\BaseController;
use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Entity\Repository\MediaFileRepository;
use Mtt\BlogBundle\Form\ImageForm;
use Mtt\BlogBundle\Model\Image;
use Mtt\BlogBundle\Service\ImageManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/mediaFiles")
 *
 * Class MediaFileController
 */
class MediaFileController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param MediaFileRepository $repository
     *
     * @return JsonResponse
     */
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
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param MediaFile $entity
     *
     * @return JsonResponse
     */
    public function findAction(MediaFile $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getMediaFile(new Image($entity));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"PUT"})
     *
     * @param Request $request
     * @param MediaFile $entity
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request, MediaFile $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->saveMediaFile($entity, $request->request->get('mediaFile'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
     *
     * @param MediaFile $entity
     * @param ImageManager $manager
     *
     * @throws ORMException
     *
     * @return JsonResponse
     */
    public function deleteAction(MediaFile $entity, ImageManager $manager): JsonResponse
    {
        $manager->remove($entity);

        return new JsonResponse(true);
    }

    /**
     * @Route("/upload", name="upload_image", options={"expose"=true}, methods={"POST"})
     *
     * @param Request $request
     * @param ImageManager $manager
     *
     * @throws ORMException
     *
     * @return JsonResponse
     */
    public function uploadAction(Request $request, ImageManager $manager): JsonResponse
    {
        $form = $this->createForm(ImageForm::class);
        $form->handleRequest($request);

        $result = null;
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
}
