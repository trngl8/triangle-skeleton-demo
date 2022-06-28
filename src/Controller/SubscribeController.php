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

    private $adminEmail;

    public function __construct(SubscribeService $subscribeService, string $adminEmail)
    {
        $this->subscribeService = $subscribeService;
        $this->adminEmail = $adminEmail;
    }

    #[Route('/list', name: 'list')]
    public function list() : Response
    {
        $items = [
            new Subscribe('test', $this->adminEmail, 'uk', 'name', ),
            new Subscribe('test2', $this->adminEmail, 'uk','name', ),
            new Subscribe('test MAX', $this->adminEmail, 'uk', 'testmax')
        ];

        return $this->render('subscribe/list.html.twig', [
            'items' => $items,
            'activeName' => 'name',
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request) : Response
    {
        $form = $this->createForm(SubscribeType::class,  new Subscribe());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
            return $this->redirectToRoute('subscribe_list');
        }

        return $this->render('subscribe/verify.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
