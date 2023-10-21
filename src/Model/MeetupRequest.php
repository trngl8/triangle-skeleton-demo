<?php

namespace App\Model;

class MeetupRequest
{
    public string $title;

    public \DateTime $plannedDayAt;
    public \DateTime $plannedTimeAt;

    public int $duration;

}
