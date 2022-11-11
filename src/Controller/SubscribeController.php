<?php

namespace App\Controller;

use App\Form\Type\SubscribeType;
use App\Form\VerifyType;
use App\Model\Subscribe;
use App\Model\Verify;
use App\Service\SubscribeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subscribe', name: 'subscribe_')]
class SubscribeController extends AbstractController
{
    private $subscribeService;

    public function __construct(SubscribeService $subscribeService)
    {
        $this->subscribeService = $subscribeService;
    }


    #[Route('/add', name: 'add')]
    public function add(Request $request) : Response
    {
        $form = $this->createForm(SubscribeType::class,  new Subscribe());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //TODO: generate event
            $this->subscribeService->initSubscribe($form->getData());

            $this->addFlash('success', 'flash.success.subscribe_created');

            return $this->redirectToRoute('subscribe_verify');
        }

        return $this->render('subscribe/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify', name: 'verify')]
    public function verify(Request $request) : Response
    {
        $verify = new Verify('test');
        $form = $this->createForm(VerifyType::class, $verify);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'flash.success.verify');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('subscribe/verify.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
