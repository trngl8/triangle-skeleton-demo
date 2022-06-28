<?php

namespace App\Controller;

use App\Button\LinkToRoute;
use App\Form\MessageType;
use App\Form\VerifyType;
use App\Model\Message;
use App\Model\Verify;
use App\Service\MessageService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    private $messageService; //TODO: should be interface

    public function __construct(MessageService $messageService)
    {
        $this->messageService  = $messageService;
    }

    public function home() : Response
    {
        $app_navbar = false;

        return $this->render('default/home.html.twig', [
            'app_navbar' => $app_navbar
        ]);
    }

    public function index(ManagerRegistry $doctrine) : Response
    {
        $app_navbar = false;

        $button = new LinkToRoute('topic_add', 'button.add');

        return $this->render('default/index.html.twig', [
            'button' => $button,
            'app_navbar' => $app_navbar
        ]);
    }

    public function info() : Response
    {
        $app_navbar = false;

        return $this->render('default/info.html.twig', [
            'app_navbar' => $app_navbar
        ]);
    }

    public function features() : Response
    {
        $app_navbar = false;

        return $this->render('default/features.html.twig', [
            'app_navbar' => $app_navbar
        ]);
    }

    public function contact(Request $request) : Response
    {
        $app_navbar = false;

        $message =  $this->messageService->create();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $this->messageService->compose($message);

            $this->messageService->send($email);

            $this->addFlash('success', $message->message);

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('default/contact.html.twig', [
            'app_navbar' => $app_navbar,
            'form' => $form->createView()
        ]);
    }
}
