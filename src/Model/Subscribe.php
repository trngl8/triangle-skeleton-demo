<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class Subscribe
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\Email]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\NotBlank]
    public $agree;

    #[Assert\NotBlank]
    public $know;

}