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

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}