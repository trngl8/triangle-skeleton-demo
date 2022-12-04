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
        return $this->forward('App\Controller\OrderController::index', [
            'request' => $request
        ]);
    }
}
