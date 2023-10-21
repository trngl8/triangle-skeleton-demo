<?php

namespace App\Model;

class MeetupRequest
{
    public string $title;

    public \DateTimeInterface $plannedAt;

    public int $duration;

}
