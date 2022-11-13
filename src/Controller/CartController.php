<?php

namespace App\Controller;

use App\Service\OfferService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class CartController extends AbstractController
{
    private $offerService;

    private $doctrine;

    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $doctrine, OfferService $offerService, LoggerInterface $logger)
    {
        $this->offerService = $offerService;
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    public function cart(Request $request) : Response
    {
        $cookie = $request->cookies->get('cart');
        $cart = $cookie ? json_decode($cookie, true) : [];

        //TODO: transform cart into stored orders
        $orders = $this->offerService->getCartOrders($cart);

        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }
}
