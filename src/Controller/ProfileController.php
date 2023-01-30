<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private $doctrine;

    private $repository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $this->doctrine->getRepository(Profile::class);
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $profile = $this->repository->findOneBy(['email' => $user->getUserIdentifier()]);

        if(!$profile) {
            //TODO: or 404 ?

            $this->addFlash('warning', 'no_active_profile');

            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->render('profile/index.html.twig', [
            'profile' => $profile,
        ]);
    }

    #[Route('/profile/invites', name: 'app_profile_invites')]
    public function invites(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $profile = $this->repository->findOneBy(['email' => $user->getUserIdentifier()]);

        if(!$profile) {
            return $this->redirectToRoute('app_profile_edit');
        }

        return $this->render('profile/invites.html.twig', [
            'profile' => $profile,
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $profile = $this->repository->findOneBy(['email' => $user->getUserIdentifier()]);

        if(!$profile) {
            $profile = (new Profile())
                ->setEmail($user->getUserIdentifier())
                ->setActive(true);
        }

        $form = $this->createForm(ProfileType::class, $profile);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $profile->setEmail($user->getUserIdentifier());
            $entityManager->persist($profile);
            $entityManager->flush();

            $this->addFlash('success', 'flash.success.profile_updated');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'profile' => $profile,
        ]);
    }
}
