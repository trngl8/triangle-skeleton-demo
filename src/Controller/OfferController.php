<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Service\OfferService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/offer", name: "app_offer_")]
class OfferController extends AbstractController
{
    private $offerService;

    private $doctrine;

    private $logger;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger, OfferService $offerService)
    {
        $this->offerService = $offerService;
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index() : Response
    {
        $user = $this->getUser();

        $offers = $this->offerService->getOffers($user);

        return $this->render('offer/index.html.twig', [
            'offers' => $offers
        ]);
    }

    #[Route('/{id}/order', name: 'order', methods: ['GET', 'POST'])]
    public function order(int $id, Request $request) : Response
    {
        $offer = $this->offerService->getOffer($id);

        //TODO: create subscribe event
        $orderRequest = $this->offerService->createOrderRequest($offer);
        $form = $this->createForm(OrderType::class, $orderRequest);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            if($user) {
                $orderRequest->deliveryEmail = $user->getUserIdentifier();
            } else {
                $orderRequest->deliveryEmail = 'test@test.com';
            }

            $order = new Order($offer, $orderRequest->deliveryEmail, $orderRequest->description);

            $this->doctrine->getManager()->persist($order);
            $this->doctrine->getManager()->flush();

            $this->logger->info('Order created', ['order' => $order]);

            $this->addFlash('success', 'flash.success.order_created');

            if($order->getStatus() === 'new') { // check init state
                $response = $this->redirectToRoute('app_order_payment', [
                    'uuid' => $order->getUuid()
                ]);
                //TODO: use session storage
                $response->headers->setCookie(new Cookie('cart', json_encode([$order->getUuid() => $orderRequest->deliveryEmail]), strtotime('tomorrow'), '/',
                    'localhost', true, true));

                return $response;
            }

            return $this->redirectToRoute('app_order_result', [
                'uuid' => $order->getUuid()
            ]);
        }

        return $this->render('offer/order.html.twig', [
            'order' => $orderRequest,
            'offer' => $offer,
            'form' => $form->createView()
        ]);
    }
}
