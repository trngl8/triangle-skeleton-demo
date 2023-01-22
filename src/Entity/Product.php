<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'app_products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageTag = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $abilities = [];

    #[ORM\Column(nullable: true)]
    private ?int $level = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $targetUrl = null;

    public function __construct()
    {
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

    public function getImageTag(): ?string
    {
        return $this->imageTag;
    }

    public function setImageTag(?string $imageTag): self
    {
        $this->imageTag = $imageTag;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAbilities(): array
    {
        return $this->abilities;
    }

    public function setAbilities(?array $abilities): self
    {
        $this->abilities = $abilities;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getTargetUrl(): ?string
    {
        return $this->targetUrl;
    }

    public function setTargetUrl(?string $targetUrl): self
    {
        $this->targetUrl = $targetUrl;

        return $this;
    }
}
