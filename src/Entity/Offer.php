<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[ORM\Table(name: 'app_offers')]
class Offer
{

    CONST BASE_BORDER = 500;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private int $amount;

    #[ORM\Column(length: 32)]
    private ?string $currency = null;

    #[ORM\OneToMany(mappedBy: 'offer', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    use TimestampTrait;

    public function __construct(string $title, string $currency, int $amount = 0)
    {
        #$this->id = 1;
        $this->title = $title;
        $this->currency = $currency;
        $this->amount = $amount;

        $this->createdAt = new \DateTimeImmutable();
        $this->orders = new ArrayCollection();
    }

    public function getOfferProperties(Product $product) : array
    {
        return [
            'level' => $product->getLevel(),
            'properties' => $product->getAbilities(),
        ];
    }

    public function __toString(): string
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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getBorder(): ?string
    {
        if($this->getAmount() > self::BASE_BORDER) {
            return 'dark';
        }

        if($this->getAmount() === self::BASE_BORDER) {
            return 'warning';
        }

        return '';
    }

    public function getPublic(): bool
    {
        return true;
    }

    public function getPrivate(): bool
    {
        return $this->active ?: $this->getAmount() >= self::BASE_BORDER;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setOffer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getOffer() === $this) {
                $order->setOffer(null);
            }
        }

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
