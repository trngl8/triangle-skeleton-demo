<?php

namespace App\Entity;

use App\Repository\MeetupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetupRepository::class)]
#[ORM\Table(name: 'app_meetups')]
class Meetup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;

    #[ORM\Column(length: 255)]
    private string $title;

    private \DateTimeInterface $plannedAt;

    private string $timezone;

    public function __construct(string $title, \DateTimeInterface $plannedAt)
    {
        $this->title = $title;
        $this->plannedAt = $plannedAt;
        $this->timezone = date_default_timezone_get();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getPlannedAt(): \DateTimeInterface
    {
        return $this->plannedAt;
    }
}