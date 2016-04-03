<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 04.04.16
 * Time: 22:34
 */

namespace Mtt\BlogBundle\Controller;

use Mtt\BlogBundle\Entity\MediaFile;
use Mtt\BlogBundle\Form\ImageForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/mediaFiles")
 *
 * Class MediaFileController
 * @package Mtt\BlogBundle\Controller
 */
class MediaFileController extends BaseController
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
            $this->getMediaFileRepository()->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getMediaFileArray($pagination, 'post');

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param MediaFile $entity
     * @return JsonResponse
     */
    public function findAction(MediaFile $entity)
    {
        $result = $this->getDataConverter()
            ->getMediaFile($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("PUT")
     *
     * @param Request $request
     * @param MediaFile $entity
     * @return JsonResponse
     */
    public function updateAction(Request $request, MediaFile $entity)
    {
        $result = $this->getDataConverter()
            ->saveMediaFile($entity, $request->request->get('mediaFile'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     * @Method("DELETE")
     *
     * @param MediaFile $entity
     * @return JsonResponse
     */
    public function deleteAction(MediaFile $entity)
    {
        $this->get('mtt_blog.image_manager')->remove($entity);
        $this->getEm()->remove($entity);
        $this->getEm()->flush();

        return new JsonResponse(true);
    }

    /**
     * @Route("/upload", name="upload_image", options={"expose"=true})
     * @Method("POST")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(new ImageForm());
        $form->submit($request);

        $result = null;
        if ($form->isValid()) {
            $result = $this->get('mtt_blog.image_manager')->uploadImage(
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

        return new JsonResponse($result);
    }
}
