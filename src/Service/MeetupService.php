<?php

namespace App\Service;

use App\Entity\Meetup;
use App\Entity\Subscribe;
use App\Repository\MeetupRepository;
use App\Service\Http\TelegramHttpClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MeetupService
{
    const SUCCESS = 1;
    const ALREADY_JOINED = 2;

    public function __construct(
        public MeetupRepository $meetupRepository,
        public TelegramHttpClient $telegramClient,
        public EntityManagerInterface $entityManager
    )
    {
    }

    public function join(Meetup $meetup, UserInterface $user): int
    {
//        $chatId = 0; //TODO define chat id
//
//        $this->telegramClient->sendMessage($chatId, sprintf(
//            'New user joined for the meetup #%d: <br>%s',
//            $meetup->getId(),
//            $user->getUserIdentifier(),
//        ));

        $exists = $this->entityManager->getRepository(Subscribe::class)->findOneBy([
            'type' => 'meetup',
            'target' => $meetup->getId(),
            'email' => $user->getUserIdentifier()
        ]);

        if ($exists) {
            return self::ALREADY_JOINED;
        }

        $subscribe = new Subscribe(
            'meetup',
            $meetup->getId(),
            $user->getUserIdentifier(),
            $user->getUserIdentifier()
        );

        $this->entityManager->persist($subscribe);
        $this->entityManager->flush();

        return self::SUCCESS;
    }

    public function subscribe(Meetup $meetup, array $data): void
    {
//        $chatId = 0; //TODO define chat id
//
//        $this->telegramClient->sendMessage($chatId, sprintf(
//            'New subscriber for the meetup #%d: <br>%s %s',
//            $meetup->getId(),
//            $data['email'],
//            $data['name']
//        ));

        $subscribe = new Subscribe(
            'meetup',
            $meetup->getId(),
            $data['email'],
            $data['name']
        );

        $this->entityManager->persist($subscribe);
        $this->entityManager->flush();

    }

    public function getSubscribers(Meetup $meetup): array
    {
        return $this->entityManager->getRepository(Subscribe::class)->findBy([
            'type' => 'meetup',
            'target' => $meetup->getId()
        ]);
    }
}
