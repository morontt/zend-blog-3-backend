<?php

namespace App\Telegram;

use App\Entity\TelegramUpdate;
use App\Entity\TelegramUser;
use Doctrine\ORM\EntityManagerInterface;
use Xelbot\Telegram\Entity\Update;
use Xelbot\Telegram\UpdatesManagerInterface;

class UpdatesManager implements UpdatesManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveUpdate(Update $obj, array $requestData)
    {
        $dbUser = null;
        $chatId = null;
        $textMessage = null;
        if ($message = $obj->getMessage()) {
            $textMessage = $message->getText();
            if ($user = $message->getFrom()) {
                $userRepository = $this->em->getRepository(TelegramUser::class);
                $dbUser = $userRepository->findOneBy(['userId' => $user->getId()]);
                if (!$dbUser) {
                    $dbUser = new TelegramUser();
                    $dbUser
                        ->setUserId($user->getId())
                        ->setFirstName($user->getFirstName())
                        ->setLastName($user->getLastName())
                        ->setUsername($user->getUsername())
                        ->setFirstName($user->getFirstName())
                        ->setBot($user->isBot())
                        ->setLanguage($user->getLanguageCode())
                    ;

                    if (isset($requestData['message']['from'])) {
                        $dbUser->setRawMessage(json_encode($requestData['message']['from'], JSON_THROW_ON_ERROR));
                    }
                    $this->em->persist($dbUser);
                    $this->em->flush();
                }
            }
            if ($chat = $message->getChat()) {
                $chatId = $chat->getId();
            }
        }

        $update = new TelegramUpdate();
        $update
            ->setRawMessage(json_encode($requestData, JSON_THROW_ON_ERROR))
            ->setTelegramUser($dbUser)
            ->setChatId($chatId)
            ->setTextMessage($textMessage)
        ;

        $this->em->persist($update);
        $this->em->flush();
    }
}
