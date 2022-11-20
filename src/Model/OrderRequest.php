<?php

namespace App\Model;
use App\Entity\Offer;
use Symfony\Component\Validator\Constraints as Assert;

class OrderRequest
{
    #[Assert\NotBlank]
    public string $title;

    #[Assert\Currency]
    #[Assert\NotBlank]
    public string $currency;

    #[Assert\Positive]
    public int $amount = 0;

    #[Assert\NotBlank]
    public string $description;

    public function __construct(Offer $offer, $description = null)
    {
        $this->title = $offer->getTitle();
        $this->currency = $offer->getCurrency();
        $this->amount = $offer->getAmount();
        $this->description = $offer->getTitle();
    }
}
