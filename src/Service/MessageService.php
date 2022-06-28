<?php

namespace App\Service;

use App\Model\Message;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MessageService implements ServiceInterface
{
    private MailerInterface $mailer;

    private $adminEmail;

    public function __construct(MailerInterface $mailer, string $adminEmail)
    {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
    }

    public function compose(Message $message) : Email
    {
        //TODO: choose email renderer
        //$email = (new Email());

        //TODO: store message
        $email = (new TemplatedEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->from($this->adminEmail) //TODO: default sender
            ->to(new Address($message->to))
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('reply@example.com')
            ->subject($message->message)
            ->htmlTemplate('email/confirm.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'message' => $message->message,
            ])
        ;
        return $email;
    }

    public function send($email) : void
    {
        $this->mailer->send($email);
    }

    public function create() : Message
    {
        return new Message($this->adminEmail);
    }
}
