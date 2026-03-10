<?php

namespace App\Controller\API;

use App\Controller\BaseController;
use App\Entity\TelegramUpdate;
use App\Repository\TelegramUpdateRepository;
use DateTime;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Xelbot\Telegram\Robot;

#[Route(path: '/api/telegramUpdates')]
class TelegramUpdateController extends BaseController
{
    /**
     * @param Request $request
     * @param TelegramUpdateRepository $repository
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['GET'])]
    public function findAllAction(Request $request, TelegramUpdateRepository $repository): JsonResponse
    {
        $pagination = $this->paginate(
            $repository->getListQuery(),
            $request->query->get('page', 1)
        );

        $result = $this->getDataConverter()
            ->getTelegramUpdateArray($pagination, 'telegramUser');

        return new JsonResponse($result);
    }

    /**
     * @param TelegramUpdate $entity
     *
     * @return JsonResponse
     */
    #[Route(path: '/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function findAction(TelegramUpdate $entity): JsonResponse
    {
        $result = $this->getDataConverter()
            ->getTelegramUpdate($entity);

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     * @param TelegramUpdateRepository $repository
     * @param Robot $bot
     *
     * @return JsonResponse
     */
    #[Route(path: '', methods: ['POST'])]
    public function createAction(Request $request, TelegramUpdateRepository $repository, Robot $bot): JsonResponse
    {
        $now = new DateTime();
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
            'createdAt' => $now->format(DateTimeInterface::ATOM),
            'replyId' => 0,
        ]);
    }
}
