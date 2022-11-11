<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    #[Assert\NotBlank]
    public string $to;

    #[Assert\NotBlank]
    public string $subject = '';

    public string $text = '';

    public function __construct(string $to)
    {
        $this->to = $to;
    }
}
