<?php

namespace App\Service;

use App\Entity\Offer;
use App\Entity\Order;
use App\Model\OrderRequest;
use App\Model\PaymentResult;
use App\Model\User;
use App\Repository\OfferRepository;
use App\Repository\OrderRepository;
use Symfony\Component\Uid\Uuid;

class OfferService
{
    private $offers;
    private $orders;

    private $paymentService; // TODO: add interface

    public function __construct(OfferRepository $offers, OrderRepository $orders, LiqpayService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->offers = $offers;
        $this->orders = $orders;
    }

    public function getOffer(int $id): Offer
    {
        return $this->offers->find($id);
    }

    public function getOrder(Uuid $id): Order
    {
        return $this->orders->findOneBy(['uuid' => $id]);
    }

    public function getCurrentOrders(): array
    {
        return $this->orders->findBy(['status' => 'new']);
    }

    public function getOffers() : array
    {
        //TODO: use criteria
        return $this->offers->findBy([]);
    }

    public function getOrders(string $key, string $identifiers) : array
    {
        return $this->orders->findBy([$key => $identifiers]);
    }

    public function getCartOrders(array $cart) : array
    {
        return $this->orders->findBy(['uuid' => array_keys($cart)]);;
    }

    public function createOrder(Offer $offer, User $user) : Order
    {
        return new Order($offer, $user->getEmail(), 'description');
    }

    public function createPayment(Order $order, array $res) : PaymentResult
    {
        return new PaymentResult($order, $res);
    }

    public function createOrderRequest(Offer $offer) : OrderRequest
    {
        return new OrderRequest($offer);
    }

    public function getFormParams(Order $order, string $resultUrl) : array
    {
        return $this->paymentService->getFormParams([
            'action'         => $order->getAction(),
            'amount'         => $order->getAmount(),
            'currency'       => $order->getCurrency(),
            'description'    => $order->getDescription(),
            'order_id'       => sprintf('XX.ZZ.YYYY.order_%d', $order->getId()),
            'result_url'     => $resultUrl,
            'version'        => '3',
            'language'       => 'en',
            'delivery' => [
                //'email' => $order->getEmail(),
            ]
        ]);
    }

    public function paymentApi(Order $order)
    {
        //TODO: check request signature
        return $this->paymentService->apiCall("request", [
            'action'        => 'status',
            'version'       => '3',
            'order_id'      => sprintf('XX.ZZ.YYYY.order_%d', $order->getId())
        ]);
    }
}
