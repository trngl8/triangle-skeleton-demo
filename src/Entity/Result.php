<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
#[ORM\Table(name: 'app_result')]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Check::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $checkItem;

    #[ORM\ManyToOne(targetEntity: Option::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $CheckOption;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $value;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $is_valid;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckItem(): ?Check
    {
        return $this->checkItem;
    }

    public function setCheckItem(?Check $checkItem): self
    {
        $this->checkItem = $checkItem;

        return $this;
    }

    public function getCheckOption(): ?Option
    {
        return $this->CheckOption;
    }

    public function setCheckOption(?Option $CheckOption): self
    {
        $this->CheckOption = $CheckOption;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->is_valid;
    }

    public function setIsValid(?bool $is_valid): self
    {
        $this->is_valid = $is_valid;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
