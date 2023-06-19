<?php

namespace App\Controller;

use App\Model\CalendarOrder;
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
        private readonly MailerInterface $mailer,
        private readonly string $adminEmail
    ) {
    }

    #[Route('', name: 'default')]
    public function default(): Response
    {
        return $this->render('default/calendar.html.twig'); //TODO: move to calendar folder
    }

    #[Route('/{date}/order', name: 'order')]
    public function order(string $date, Request $request): Response
    {
        $order = new CalendarOrder();
        $form = $this->createFormBuilder($order)
            ->add('date', HiddenType::class, [
                'data' => $date,
            ])
            ->add('name')
            ->add('email')
            ->add('phone')
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $order->date = $date;
            $email = (new TemplatedEmail())
                ->from(new Address('info@triangle.software', 'bot')) //TODO: create sender
                ->to($this->adminEmail)
                ->subject('new order')
                ->htmlTemplate('email/new_order.html.twig')
                ->context([
                    'order' => $order,
                ])
            ;

            $this->mailer->send($email);

            $this->addFlash('success', 'Your order has been sent');

            return $this->redirectToRoute('app_calendar_order_success');
        }

        return $this->render('default/calendar_order.html.twig', [
            'time' => $date,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/order/success', name: 'order_success')]
    public function orderSuccess(): Response
    {
        return $this->render('default/calendar_order_success.html.twig');
    }
}
