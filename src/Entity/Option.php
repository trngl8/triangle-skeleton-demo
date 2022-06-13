<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: 'app_options')]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $title;

    #[ORM\ManyToOne(targetEntity: Check::class, inversedBy: 'options')]
    #[ORM\JoinColumn(nullable: false)]
    private $parent;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'integer')]
    private $position;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private $open;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $correct;

    public function __construct()
    {
        $this->open = false;
        $this->type = 'default';
        $this->position = 0;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getParent(): ?Check
    {
        return $this->parent;
    }

    public function setParent(Check $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getCorrect(): ?bool
    {
        return $this->correct;
    }

    public function setCorrect(?bool $correct): self
    {
        $this->correct = $correct;

        return $this;
    }
}
