<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    #[Assert\NotBlank]
    public string $to;

    #[Assert\NotBlank]
    public string $subject;

    public ?string $message;

    public function __construct(string $to, string $subject)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $subject;
    }
}
