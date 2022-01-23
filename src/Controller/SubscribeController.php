<?php

namespace App\Controller;

use App\Form\Type\SubscribeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends AbstractController
{
    public function index(Request $request) : Response
    {
        $form = $this->createForm(SubscribeType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //TODO: complete request

            $this->addFlash('success', 'flash.success.subscribe_created');

            return $this->redirectToRoute('app_subscribe_success');
        }

        return $this->render('subscribe/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function success() : Response
    {
        return $this->render('subscribe/success.html.twig');
    }
}