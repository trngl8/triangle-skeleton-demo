<?php

namespace App\Service;

use App\Entity\Meetup;
use App\Repository\MeetupRepository;
use App\Service\Http\TelegramHttpClient;
use Symfony\Component\Security\Core\User\UserInterface;

class MeetupService
{
    public function __construct(
        public readonly MeetupRepository $meetupRepository,
        public readonly TelegramHttpClient $telegramClient
    )
    {
    }

    public function join(Meetup $meetup, UserInterface $user): void
    {
        $chatId = 0; //TODO define chat id

        $this->telegramClient->sendMessage($chatId, sprintf(
            'New user joined for the meetup #%d: <br>%s',
            $meetup->getId(),
            $user->getUserIdentifier(),
        ));
    }

    public function subscribe(Meetup $meetup, array $data): void
    {
        $chatId = 0; //TODO define chat id

        $this->telegramClient->sendMessage($chatId, sprintf(
            'New subscriber for the meetup #%d: <br>%s %s',
            $meetup->getId(),
            $data['email'],
            $data['name']
        ));

    }
}
