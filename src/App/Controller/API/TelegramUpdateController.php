<?php

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Entity\TelegramUpdate;
use App\Repository\TelegramUpdateRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Xelbot\Telegram\Robot;

/**
 * @Route("/api/telegramUpdates")
 */
class TelegramUpdateController extends BaseController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request $request
     * @param TelegramUpdateRepository $repository
     *
     * @return JsonResponse
     */
    public function findAllAction(Request $request, TelegramUpdateRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getTelegramUpdateArray($pagination, 'telegramUser');

        $result['meta'] = $this->getPaginationMetadata($pagination->getPaginationData());

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param TelegramUpdate $entity
     *
     * @return JsonResponse
     */
    public function findAction(TelegramUpdate $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getTelegramUpdate($entity);

        return new JsonResponse($result);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @param Request $request
     * @param TelegramUpdateRepository $repository
     * @param Robot $bot
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, TelegramUpdateRepository $repository, Robot $bot): JsonResponse
    {
        $now = new \DateTime();
        $messageData = $request->request->get('telegramUpdate');
        $message = $messageData['message'];
        $replyId = $messageData['replyId'];

        if ($tgUpdate = $repository->find($replyId)) {
            if ($chatId = $tgUpdate->getChatId()) {
                $bot->sendMessage($message, $chatId);
            }
        } else {
            throw $this->createNotFoundException();
        }

        /* virtual Telegram update :) */
        return new JsonResponse([
            'id' => (int)$now->format('U'),
            'user' => null,
            'message' => $message,
            'createdAt' => $now->format(\DateTimeInterface::ATOM),
            'replyId' => 0,
        ]);
    }
}
