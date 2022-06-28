<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class Subscribe
{
    CONST DEFAULT_TYPE = 'email'; //direct

    static $id;

    #[Assert\NotBlank]
    private string $type;

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

    public function __construct(?string $type = null, string $email = null, string $locale = null, ?string $name = null)
    {
        $this->type = $type ?? self::DEFAULT_TYPE;
        $this->locale = $locale ?? 'uk';
        $this->name = $name ?? 'test';
        $this->email = $email ?? 'test';
    }

    public function getId()
    {
        return self::$id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTitle()
    {
        return $this->name;
    }

}
