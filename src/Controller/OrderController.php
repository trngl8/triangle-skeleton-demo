<?php

namespace App\Controller;

use App\Model\PaymentResult;
use App\Service\OfferService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

#[Route(path: "/order", name: "app_order_")]
class OrderController extends AbstractController
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

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request) : Response
    {
        $cart = $request->getSession()->get('cart', []);

        $orders = $this->offerService->getCartOrders($cart);

        $user = $this->getUser();

        if($user) {
            $orders = array_merge($orders, $this->offerService->getOrders('deliveryEmail', $user->getUserIdentifier()));
        }

        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/{uuid}/payment', name: 'payment', methods: ['GET'])]
    public function payment(Uuid $uuid) : Response
    {
        $order = $this->offerService->getOrder($uuid);

        $formParams = $this->offerService->getFormParams($order, $this->generateUrl('app_order_result', ['uuid' => $order->getUuid()], UrlGeneratorInterface::ABSOLUTE_URL));

        return $this->render('order/payment.html.twig', [
            'form' => $formParams,
            'order' => $order
        ]);
    }

    #[Route('/{uuid}/status', name: 'status', methods: ['GET'])]
    public function status(Uuid $uuid, Request $request) : Response
    {
        $order = $this->offerService->getOrder($uuid);

        if ($order->getStatus() === 'new') {
            return $this->redirectToRoute('app_order_payment', ['uuid' => $uuid]);
        }

        return $this->render('order/status.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{uuid}/result', name: 'result', methods: ['POST'])]
    public function result(Uuid $uuid, Request $request) : Response
    {
        $order = $this->offerService->getOrder($uuid);

        $res = $this->offerService->paymentApi($order);

        $payment = new PaymentResult($order, $res);

        if($payment->getStatus() === 'error') {
            $order->setStatus('error');
            $this->addFlash('error', sprintf('flash.error.payment_error %s', $res['err_code']));
            $this->logger->error(sprintf('Payment error: %s', $res['err_code']));
        }

        if($payment->getStatus() === 'success' ) {
            $request->getSession()->set('cart', []);

            $order->setStatus('paid');

            $this->doctrine->getManager()->flush();
            $this->logger->info(sprintf('Order %d %s paid (%s). Total: %d%s', $order->getId(), $order->getUuid(), $payment->getStatus(), $order->getAmount(), $order->getCurrency()), ['order' => $order, 'payment' => $payment]);

            $this->addFlash('success', 'flash.success.order_success');
        }

        return $this->redirectToRoute('app_order_status', ['uuid' => $order->getUuid()]);
    }
}
