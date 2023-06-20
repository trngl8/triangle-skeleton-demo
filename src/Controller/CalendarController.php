<?php

namespace App\Controller;

use App\Entity\Order;
use App\Model\CalendarOrder;
use App\Repository\OrderRepository;
use App\Repository\TimeDataRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/calendar', name: 'app_calendar_')]
class CalendarController extends AbstractController
{
    public function __construct(
        private readonly TimeDataRepository $timeDataRepository,
        private readonly OrderRepository $orderRepository,
        private readonly MailerInterface $mailer,
        private readonly string $adminEmail
    ) {
    }

    #[Route('', name: 'default')]
    public function default(): Response
    {
        $todayItems = $this->timeDataRepository->findToday();
        $tomorrowItems = $this->timeDataRepository->findTomorrow();

        return $this->render('calendar/default.html.twig', [
            'todayItems' => $todayItems,
            'tomorrowItems' => $tomorrowItems,
        ]);
    }

    #[Route('/{uuid}/order', name: 'order')]
    public function order(string $uuid, Request $request): Response
    {
        $item = $this->timeDataRepository->get($uuid);

        if(!$item) {
            throw $this->createNotFoundException(sprintf('Item %s not found', $uuid));
        }

        $orderRequest = new CalendarOrder();
        $form = $this->createFormBuilder($orderRequest)
            ->add('date', HiddenType::class, [
                'data' => $uuid,
            ])
            ->add('name')
            ->add('email')
            ->add('phone')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderRequest->date = $item['time'];

            //TODO: create from Order factory
            //TODO: deliveryName

            $order = new Order();
            $order->setAmount(1);
            $order->setCurrency('USD');
            $order->setDeliveryEmail($orderRequest->email);
            $order->setDeliveryPhone($orderRequest->phone);
            $order->setDescription(sprintf("%s %s", $orderRequest->name,  $orderRequest->date));
            $this->orderRepository->add($order, true);
            $email = (new TemplatedEmail())
                ->from(new Address('info@triangle.software', 'Triangle Software')) //TODO: create sender
                ->to($this->adminEmail) //TODO: should be calendar owner email
                ->subject('new order')
                ->htmlTemplate('email/new_order.html.twig')
                ->context([
                    'order' => $orderRequest,
                ])
            ;

            $this->mailer->send($email);

            $this->addFlash('success', sprintf('Your order %s has been sent', $order->getUuid()));

            //TODO: redirect to success Order page
            return $this->redirectToRoute('app_calendar_order_success');
        }

        return $this->render('calendar/calendar_order.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/order/success', name: 'order_success')]
    public function orderSuccess(): Response
    {
        return $this->render('calendar/calendar_order_success.html.twig');
    }
}
