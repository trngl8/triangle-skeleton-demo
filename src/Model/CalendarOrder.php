<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CalendarOrder
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $email;

    #[Assert\NotBlank]
    public string $phone;

    #[Assert\NotBlank]
    public string $date;
}
