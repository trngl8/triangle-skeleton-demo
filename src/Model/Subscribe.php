<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class Subscribe
{
    CONST DEFAULT_TYPE = 'email'; //direct

    #[Assert\NotBlank]
    private string $type = self::DEFAULT_TYPE;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\Email]
    #[Assert\NotBlank]
    public string $email;

    #[Assert\NotBlank]
    public string $locale;

    #[Assert\NotBlank]
    public $agree;

    #[Assert\NotBlank]
    public $know;
}
