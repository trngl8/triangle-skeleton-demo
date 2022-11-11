<?php

namespace App\Controller;

use App\Form\MessageType;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InboxController extends AbstractController
{
    private $messageService; //TODO: should be interface

    public function __construct(MessageService $messageService)
    {
        $this->messageService  = $messageService;
    }

    public function contact(Request $request) : Response
    {
        $message = $this->messageService->create();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $this->messageService->compose($message);

            $this->messageService->send($email);

            $this->messageService->save($message);

            $this->addFlash('success', $message->text);

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function inbox() : Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $messages = $this->messageService->findIncoming($user->getUserIdentifier());

        return $this->render('default/inbox.html.twig', ['inbox' => $messages]);
    }
}
