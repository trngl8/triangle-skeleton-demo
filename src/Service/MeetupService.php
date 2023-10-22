<?php

namespace App\Service;

use App\Entity\Meetup;
use Symfony\Component\Security\Core\User\UserInterface;

class MeetupService
{
    public function __construct()
    {
        //
    }

    public function join(Meetup $meetup, UserInterface $user): void
    {
    }

    public function subscribe(Meetup $meetup, array $data): void
    {
    }
}
