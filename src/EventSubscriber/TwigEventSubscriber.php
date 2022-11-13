<?php

namespace App\EventSubscriber;

use App\Service\MessageService;
use App\Service\OfferService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;

    private $security;

    private $messageService;

    private $offerService;

    public function __construct(Environment $twig, Security $security, MessageService $messageService, OfferService $offerService)
    {
        $this->twig = $twig;
        $this->security = $security;
        $this->messageService = $messageService;
        $this->offerService = $offerService;
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        //TODO: check cart in session

        // Check cart in cookies
        $request = $event->getRequest();
        $cookie = $request->cookies->get('cart');
        $cart = $cookie ? json_decode($cookie, true) : [];

        if($cart) {
            $cartItems = $this->offerService->getCartOrders($cart);

            if(count($cartItems) > 0) {
                $this->twig->addGlobal('cart_items_count', count($cartItems));
            }
        }

        // Check user messages
        $user = $this->security->getUser();
        if(!$user)  {
            return;
        }

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
