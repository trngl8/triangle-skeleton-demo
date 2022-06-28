<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class InviteAccept
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\Email]
    #[Assert\NotBlank]
    public string $email;

    public string $locale;

    #[Assert\NotBlank]
    public $agree;

    #[Assert\NotBlank]
    public $know;

}
