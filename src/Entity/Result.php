<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
#[ORM\Table(name: 'app_results')]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: Check::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $checkItem;

    #[ORM\ManyToOne(targetEntity: Option::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $checkOption;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $value;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $is_valid;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
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
        return $this->checkOption;
    }

    public function setCheckOption(?Option $checkOption): self
    {
        $this->checkOption = $checkOption;

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
