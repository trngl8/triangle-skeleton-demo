<?php

namespace App\Controller;

use App\Service\OfferService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends AbstractController
{
    private $offerService;

    private LoggerInterface $logger;

    public function __construct(OfferService $offerService, LoggerInterface $logger)
    {
        $this->offerService = $offerService;
        $this->logger = $logger;
    }

    public function cart(Request $request) : Response
    {
        $cookie = $request->cookies->get('cart');
        $cart = $cookie ? json_decode($cookie, true) : [];

        //TODO: get cart products
        $orders = $this->offerService->getCartOrders($cart);

        $response = $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);

        $response->headers->setCookie(new Cookie('cart', json_encode([$order->getUuid()]), strtotime('-1 day'), '/',
            'localhost', true, true));

        return $response;
    }
}
