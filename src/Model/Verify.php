<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Verify
{
    #[Assert\NotBlank]
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function isValid()
    {
        return true;
    }
}
