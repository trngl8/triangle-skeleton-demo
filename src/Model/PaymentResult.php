<?php

namespace App\Model;
use App\Entity\Order;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentResult
{
    #[Assert\Currency]
    #[Assert\NotBlank]
    public string $currency;

    #[Assert\Positive]
    public int $amount = 0;

    #[Assert\NotBlank]
    public string $description;

    private string $status;

    public function __construct(Order $offer, array $res)
    {
        $this->currency = $offer->getCurrency();
        $this->amount = $offer->getAmount();
        $this->description = $offer->getDescription();

        //TODO: map res yo the object
        $this->status = $res['status'];
    }

    public function getStatus() : string
    {
        return $this->status;
    }
}
