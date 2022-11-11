<?php

namespace App\EventSubscriber;

use App\Repository\ProfileRepository;
use App\Repository\TopicRepository;
use App\Service\MessageService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;

    private $security;

    private $messageService;

    public function __construct(Environment $twig, Security $security, MessageService $messageService)
    {
        $this->twig = $twig;
        $this->security = $security;
        $this->messageService = $messageService;
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $user = $this->security->getUser();
        if(!$user)  {
            return;
        }

        //TODO: get unread count
        $countMessages = $this->messageService->findIncomingCount($user->getUserIdentifier());

        if($countMessages > 0) {
            $this->twig->addGlobal('incoming_unread_count', $countMessages);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
