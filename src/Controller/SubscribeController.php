<?php

namespace App\Controller;

use App\Form\Type\SubscribeType;
use App\Model\Subscribe;
use App\Repository\InviteRepository;
use App\Service\SubscribeService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subscribe', name: 'subscribe_')]
class SubscribeController extends AbstractController
{
    private $doctrine;

    private $subscribeService;

    private $inviteRepository;

    public function __construct(ManagerRegistry $doctrine, SubscribeService $subscribeService, InviteRepository $inviteRepository)
    {
        $this->doctrine = $doctrine;
        $this->subscribeService = $subscribeService;
        $this->inviteRepository = $inviteRepository;
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request) : Response
    {
        $user = $this->getUser();

        if($user) {
            $this->addFlash('warning', 'flash.warning.already_logged_in');

            return $this->redirectToRoute('app_profile_invites');
        }

        $subscription = new Subscribe();
        $form = $this->createForm(SubscribeType::class,  $subscription);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //TODO: check if user exists
            //TODO: check an exception
            $this->subscribeService->initSubscribe($subscription);

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
        $token = $request->query->get('token');

        $invite = $this->inviteRepository->findOneBy(['description' => $token]);

        if($token && $invite) {
            //TODO: implement verified flag
            $invite->setDescription('');

            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'flash.success.verify');

            return $this->redirectToRoute('app_profile');
        }

        $this->addFlash('error', 'flash.error.invalid_token');

        return $this->redirectToRoute('login');
    }
}
