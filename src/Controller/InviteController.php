<?php

namespace App\Controller;

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

    #[Route('/i/{code}', name: 'app_invite_accept')]
    public function index(string $code, Request $request): Response
    {
        $app_navbar = false;
        $accept = new InviteAccept();
        $accept->name = $code;

        $form = $this->createForm(InviteAcceptType::class, $accept);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $profile = (new Profile())
                ->setName($accept->name)
                ->setEmail($accept->email)
                ->setTimezone( 'default')
                ->setLocale('uk')
                ->setActive(true)
            ;

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            $this->addFlash('profile', 'created');
            $this->addFlash('success', 'flash.success.subscribe_created');
        }

        return $this->render('invite/accept.html.twig', [
            'code' => $code,
            'app_navbar' => $app_navbar,
            'form' => $form->createView()
        ]);
    }
}
