<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class OfferRequest
{
    #[Assert\NotBlank]
    public string $title;

    #[Assert\Currency]
    #[Assert\NotBlank]
    public string $currency;

    #[Assert\Positive]
    public int $amount = 0;
}
