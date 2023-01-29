<?php

namespace App\Controller;

use App\Entity\Invite;
use App\Entity\Profile;
use App\Form\InviteAcceptType;
use App\Model\InviteAccept;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InviteController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/invite/accept/{id}', name: 'app_invite_accept')]
    public function index(int $id, Request $request): Response
    {
        $invite = $this->doctrine->getRepository(Invite::class)->find($id);
        $accept = new InviteAccept();
        $accept->email = $invite->getEmail();
        $accept->name = $invite->getName();

        $form = $this->createForm(InviteAcceptType::class, $accept);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $profile = (new Profile())
                ->setName($accept->name)
                ->setEmail($accept->email)
                ->setTimezone( 'Europe/Kyiv')
                ->setLocale('uk')
                ->setActive(true)
            ;

            //TODo: create user
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.created');

            $this->redirectToRoute('login');
        }

        return $this->render('invite/accept.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
